<?php

namespace App\Controller;


use App\Entity\Etablissement;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EtablissementController extends AbstractController
{
    #[Route('/etablissements', name: 'app_etablissement_index')]
    public function index(EtablissementRepository $etablissementRepository): Response
    {
        $allEtablissements = $etablissementRepository->findAll();

        return $this->render('etablissement/index.html.twig', [
            'etablissements' => $allEtablissements,
        ]);
    }

    #[Route('/update', name: 'app_etablissement_update')]
    public function update(EntityManagerInterface $em): Response
    {
        $id = 14; // ID à modifier

        // Récupération de l'entité via EntityManager
        $etablissement = $em->getRepository(Etablissement::class)->find($id);

        if(!$etablissement){
            throw $this->createNotFoundException("Etablissement n'existe pas");
        }

        $etablissement->setDescription('Description modifiée');
        $em->flush();

        return new Response("Etablissement #{$id} mis à jour");
    }
}
