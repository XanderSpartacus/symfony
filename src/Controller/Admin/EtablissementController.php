<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use App\Form\EtablissementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted("ROLE_ADMIN")]
#[Route('/admin/etablissement')]
class EtablissementController extends AbstractController
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    #[Route('/new', name: 'admin_etablissement_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($etablissement);
            $manager->flush();

            $this->addFlash('success', $this->translator->trans('etablissement.flash.create_success'));

            return $this->redirectToRoute('app_etablissement_index');
        }

        return $this->render('etablissement/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_etablissement_edit')]
    public function edit(Request $request, Etablissement $etablissement, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', $this->translator->trans('etablissement.flash.edit_success'));

            return $this->redirectToRoute('app_etablissement_index');
        }

        return $this->render('admin/etablissement/edit.html.twig', [
            'form' => $form->createView(),
            'etablissement' => $etablissement,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_etablissement_delete', methods: ['POST'])]
    public function delete(Request $request, Etablissement $etablissement, EntityManagerInterface $manager): Response
    {
        # https://symfony.com/doc/current/security/csrf.html
        $token = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete-etablissement'.$etablissement->getId(), $token)) {
            $manager->remove($etablissement);
            $manager->flush();

            $this->addFlash('success', $this->translator->trans('etablissement.flash.delete_success'));
        } else {
            $this->addFlash('danger', $this->translator->trans('csrf.token_invalid'));
        }

        return $this->redirectToRoute('app_etablissement_index');
    }
}
