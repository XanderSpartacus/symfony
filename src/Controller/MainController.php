<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/a-propos', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig');
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
