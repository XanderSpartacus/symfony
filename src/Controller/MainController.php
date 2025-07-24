<?php

namespace App\Controller;

use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(StatsService $statsService): Response
    {
        $etablissementsCount = $statsService->getEtablissementsCount();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'etablissementsCount' => $etablissementsCount,
        ]);
    }

    #[Route('/a-propos', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig');
    }

    #[Route('/change-locale/{locale}', name: 'app_change_locale')]
    public function changeLocale(string $locale, Request $request): Response
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ticket/{name}', name: 'app_ticket', defaults: ['name' => 'ticket'])]
    public function ticket(string $name): Response
    {
        return new Response('Votre ticket d\'aide : ' . $name . ' !');
    }

    #[Route('/book/{id}', name: 'app_book', requirements: ['id' => '\d+'])]
    public function book(int $id): Response
    {
        return new Response('Votre livre a pour identifiant : ' . $id . ' !');
    }
}
