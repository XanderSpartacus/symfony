<?php
# https://symfony.com/doc/current/security/expressions.html

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    // isGranted() méthode et #[IsGranted] l'attribut acceptent également un objet Expression :

    /*#[IsGranted(new expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_PARTNER")'))]
    #[IsGranted(new Expression(
        '"ROLE_ADMIN" in role_names or (is_authenticated() and user.isSuperAdmin())'
    ))]*/

    #[IsGranted(new expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_PARTNER")'))]
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/user', name: 'user_dashboard')]
    public function userDashboard(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[IsGranted("ROLE_PARTNER")]
    #[Route('/partner', name: 'partner_dashboard')]
    public function partnerDashboard(): Response
    {
        return $this->render('partner/index.html.twig');
    }

}
