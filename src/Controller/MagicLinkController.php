<?php

namespace App\Controller;

use App\Repository\LoginTokenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagicLinkController extends AbstractController
{
    #[Route('/partner/login/{token}', name: 'partner_magic_login')]
    public function login(string $token, LoginTokenRepository $loginTokenRepository): Response
    {
        // La logique de connexion sera gérée par l'authenticator.
        // Ce contrôleur est principalement un point d'entrée pour la route.
        // Si l'authenticator échoue, il lèvera une exception que Symfony
        // transformera en une page d'erreur (403, 404, etc.).

        // Si l'authenticator réussit, l'utilisateur sera redirigé
        // vers son dashboard avant même d'atteindre ce return.
        // On peut mettre ici un message pour le cas où quelque chose
        // d'inattendu se produit.
        return new Response("La page de connexion magique est en cours de construction.", 404);
    }
}
