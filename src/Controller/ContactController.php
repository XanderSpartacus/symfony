<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactData = $form->getData();

            $contactForm = (new TemplatedEmail())
                ->from(new Address($contactData['mail'], $contactData['name']))
                ->to(new Address('contact@test.dev', 'CSIESR Contact'))
                ->subject('Nouveau message de contact')
                ->htmlTemplate('emails/contact_email.html.twig')
                ->context([
                    'name' => $contactData['name'],
                    'mail' => $contactData['mail'],
                    'message' => $contactData['message'],
                ]);
            $mailer->send($contactForm);

            $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons dès que possible.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
