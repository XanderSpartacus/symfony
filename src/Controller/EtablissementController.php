<?php

namespace App\Controller;


use App\Entity\Etablissement;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use App\Service\StatsService;
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
        Request $request,
        StatsService $statsService
    ): Response {
        $query = $etablissementRepository->createQueryBuilder('e')
            ->orderBy('e.nom', 'ASC');
            //->getQuery()->getResult();

        $etablissements = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $etablissementsCount = $statsService->getEtablissementsCount();

        $response = $this->render('etablissement/index.html.twig', [
            'etablissements' => $etablissements,
            'etablissementsCount' => $etablissementsCount,
        ]);

        # https://symfony.com/doc/current/components/http_foundation.html#managing-the-http-cache
        /*$response->setCache([
            'must_revalidate'  => false,
            'no_cache'         => false,
            'no_store'         => false,
            'no_transform'     => false,
            'public'           => true,
            'private'          => false,
            'proxy_revalidate' => false,
            'max_age'          => 600,
            's_maxage'         => 600,
            'stale_if_error'   => 86400,
            'stale_while_revalidate' => 60,
            'immutable'        => true,
            'last_modified'    => new \DateTime(),
            'etag'             => 'abcdef',
        ]);*/

        $response->headers->set('X-Debug-Token', 'null'); // désactive le profiler pour cette réponse
        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    #[Route('/etablissement/{id}', name: 'app_etablissement_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Etablissement $etablissement): Response
    {
        return $this->render('etablissement/show.html.twig', [
            'etablissement' => $etablissement,
        ]);
    }

}
