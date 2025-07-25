<?php

namespace App\Controller\Admin;

use App\Form\InvitationFormType;
use App\Service\InvitationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/invite')]
#[IsGranted('ROLE_ADMIN')]
class InvitationController extends AbstractController
{
    #[Route('', name: 'admin_invitation_form')]
    public function invite(Request $request, InvitationService $invitationService): Response
    {
        $form = $this->createForm(InvitationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $duration = $form->get('duration')->getData();

            $invitationService->sendInvitation($email, $duration, $this->getUser());

            $this->addFlash('success', "L'invitation a bien été envoyée à {$email}.");

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/invitation/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
