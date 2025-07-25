<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PartnerFixtures extends Fixture implements FixtureGroupInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $partner = new User();
        $partner->setEmail('partner@test.dev');
        $partner->setRoles(['ROLE_PARTNER']);
        $partner->setPassword($this->passwordHasher->hashPassword($partner, 'partner'));
        $partner->setIsVerified(true);

        $manager->persist($partner);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group3'];
    }
}
