<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\ByteString;

#[Route('/admin/')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('user/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $temporaryPassword = ByteString::fromRandom(12)->toString(); // mot de passe aléatoire 12 caractères
            $hashedPassword = $passwordHasher->hashPassword($user, $temporaryPassword);
            $user->setPassword($hashedPassword);

            $user->setRoles($form->get('roles')->getData());

            $em->persist($user);
            $em->flush();

            // generate a signed url and email it to the user
            $emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('noreply@test.dev', 'Portail CSIESR'))
                    ->to((string) $user->getEmail())
                    ->subject('Merci de confirmer votre adresse e-mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context([
                        'temporaryPassword' => $temporaryPassword,
                    ])
            );

            $this->addFlash('success', 'Enregistrement du nouvel utilisateur réalisé avec succès !');

            $this->addFlash('success', 'Un e-mail de confirmation a été envoyé au nouvel utilisateur. Il devra valider son mail pour se connecter.');

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('user/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $em->flush();

            $this->addFlash('success', 'Modification de l\'utilisateur réalisé avec succès !');

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('user/{id}/delete', name: 'admin_user_delete', methods: ['GET', 'POST'])]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Suppression de l\'utilisateur réalisé avec succès !');

        return $this->redirectToRoute('admin_users_list');
    }

    #[Route('users', name: 'admin_users_list', methods: ['GET', 'POST'])]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
