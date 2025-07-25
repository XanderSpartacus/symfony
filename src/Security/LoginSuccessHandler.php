<?php
# https://symfony.com/doc/current/security.html
# https://symfony.com/doc/current/security.html
# https://symfony.com/doc/current/security.html

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    private RouterInterface $router;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(RouterInterface $router, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?RedirectResponse
    {
        // TODO: Implement onAuthenticationSuccess() method.
        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('admin_dashboard'));
        } elseif($this->authorizationChecker->isGranted('ROLE_USER')) {
            $response = new RedirectResponse($this->router->generate('user_dashboard'));
        } elseif ($this->authorizationChecker->isGranted('ROLE_PARTNER')) {
            $response = new RedirectResponse($this->router->generate('partner_dashboard'));
        } else {
            $response = new RedirectResponse($this->router->generate('app_main'));
        }

        return $response;
    }
}
