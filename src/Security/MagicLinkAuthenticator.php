<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\LoginTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class MagicLinkAuthenticator extends AbstractAuthenticator
{
    private LoginTokenRepository $loginTokenRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        LoginTokenRepository $loginTokenRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->loginTokenRepository = $loginTokenRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'partner_magic_login';
    }

    public function authenticate(Request $request): Passport
    {
        $tokenValue = $request->attributes->get('token');
        if (null === $tokenValue) {
            throw new CustomUserMessageAuthenticationException('Token manquant.');
        }

        $loginToken = $this->loginTokenRepository->findOneBy(['token' => $tokenValue]);

        if (null === $loginToken || $loginToken->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Ce lien est invalide ou a expiré.');
        }

        $email = $loginToken->getInvitedUserEmail();
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (null === $user) {
            $user = new User();
            $user->setEmail($email);
            $user->setRoles(['ROLE_PARTNER']);
            // Pas de mot de passe nécessaire pour la connexion magique
            $user->setPassword(bin2hex(random_bytes(16)));
            $user->setIsVerified(true);
            $this->entityManager->persist($user);
        }

        // Mettre à jour le rôle si l'utilisateur existe déjà mais n'est pas partenaire
        if (!in_array('ROLE_PARTNER', $user->getRoles())) {
            $roles = $user->getRoles();
            $roles[] = 'ROLE_PARTNER';
            $user->setRoles(array_unique($roles));
        }

        // On peut supprimer le token après utilisation pour qu'il ne soit plus valide
        // $this->entityManager->remove($loginToken); // décommenter si on veut une connexion unique
        $this->entityManager->flush();

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Rediriger vers le dashboard partenaire après une connexion réussie
        return new RedirectResponse($this->urlGenerator->generate('partner_dashboard'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set('_security.main.error', $exception);

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}
