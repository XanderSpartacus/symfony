<?php

namespace App\Controller;


use App\Entity\Etablissement;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EtablissementController extends AbstractController
{
    #[Route('/etablissements', name: 'app_etablissement_index')]
    public function index(
        EtablissementRepository $etablissementRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $etablissementRepository->createQueryBuilder('e')
            ->orderBy('e.nom', 'ASC');
            //->getQuery()->getResult();

        $etablissements = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        //dd($etablissements); // debug pour voir les données paginées reçues par le template

        // plan d'action :
        // on va installer le bundle de pagination (x)
        // implémenter dans le controller le service de pagination 'paginate'
        // mettre à jour le template Twig etablissement/index.html.twig

        return $this->render('etablissement/index.html.twig', [
            'etablissements' => $etablissements,
        ]);
    }

    #[Route('/etablissement/new', name: 'app_etablissement_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($etablissement);
            $manager->flush();

            $this->addFlash('success', 'L\'Établissement a bien été ajouté');

            return $this->redirectToRoute('app_etablissement_index');
        }

        return $this->render('etablissement/new.html.twig', [
            'form' => $form
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
