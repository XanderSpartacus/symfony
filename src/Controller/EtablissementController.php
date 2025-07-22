<?php

namespace App\Controller;


use App\Entity\Etablissement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EtablissementController extends AbstractController
{
    #[Route('/etablissements', name: 'app_etablissement_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $etablissement = new Etablissement();
        $etablissement->setNom('Université de Strasbourg');
        $etablissement->setVille('Strasbourg');
        $etablissement->setDescription("Description de l'Université de Strasbourg");

        // dit à Doctrine qu'éventuellement on va enregistrer l'établissement
        $em->persist($etablissement);
        // c'est le persist qui va enregistrer la donnée et effectuer la query (INSERT, UPDATE, DELETE) dans une transaction
        $em->flush();

        return new Response('Entité  avec ID ' . $etablissement->getId() . ' a été enregistrée avec succes');
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
