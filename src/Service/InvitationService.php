<?php

namespace App\Service;

use App\Entity\LoginToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InvitationService
{
    private EntityManagerInterface $manager;
    private MailerInterface $mailer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $manager,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->manager = $manager;
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    public function sendInvitation(string $email, int $durationHours, User $admin): void
    {
        $token = new LoginToken();
        $token->setInvitedUserEmail($email);
        $token->setCreatedBy($admin);
        $token->setExpiresAt(new \DateTimeImmutable("+{$durationHours} hours"));
        $token->setToken(bin2hex(random_bytes(32)));

        $this->manager->persist($token);
        $this->manager->flush();

        $magicLink = $this->urlGenerator->generate('partner_magic_login', [
            'token' => $token->getToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $emailMessage = (new Email())
            ->from('no-reply@test.dev')
            ->to($email)
            ->subject('Votre invitation à notre portail partenaire')
            ->html("<p>Bonjour,</p><p>Vous avez été invité à accéder à notre espace partenaire. Veuillez cliquer sur le lien suivant pour vous connecter. Ce lien est valide pour {$durationHours} heures.</p><p><a href='{$magicLink}'>Accéder à l'espace partenaire</a></p>");

        $this->mailer->send($emailMessage);
    }
}
