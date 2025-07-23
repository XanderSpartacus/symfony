<?php

namespace App\DataFixtures;

use App\Entity\Etablissement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // cette commande permet de charger les fixtures mais elle purge la table : bin/console doctrine:fixtures:load
        // ajoute les données à celles existantes : bin/console doctrine:fixtures:load --append
        // voir d'autres options : bin/console doctrine:fixtures:load --help

        $etablissements = [
            ['nom' => 'Université de Marseille', 'ville' => 'Marseille', 'description' => "Description de l'Université de Marseille", 'site_internet' => 'https://www.univ-amu.fr', 'telephone' => '+33 4 13 94 51 00'],
            ['nom' => 'Université de Lille', 'ville' => 'Lille', 'description' => "Description de l'Université de Lille", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Pau', 'ville' => 'Pau', 'description' => "Description de l'Université de Pau", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Rennes', 'ville' => 'Rennes', 'description' => "Description de l'Université de Rennes", 'site_internet' => 'https://www.univ-rennes.fr', 'telephone' => '+33 2 23 23 37 54'],
            ['nom' => 'Université de Nantes', 'ville' => 'Nantes', 'description' => "Description de l'Université de Nantes", 'site_internet' => 'https://www.univ-nantes.fr', 'telephone' => '+33 2 40 99 83 83'],
            ['nom' => 'Université de Bordeaux', 'ville' => 'Bordeaux', 'description' => "Description de l'Université de Bordeaux", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Lyon', 'ville' => 'Lyon', 'description' => "Description de l'Université de Lyon", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Nice', 'ville' => 'Nice', 'description' => "Description de l'Université de Nice", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Paris', 'ville' => 'Paris', 'description' => "Description de l'Université de Paris", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Rouen', 'ville' => 'Rouen', 'description' => "Description de l'Université de Rouen", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Mulhouse', 'ville' => 'Mulhouse', 'description' => "Description de l'Université de Mulhouse", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Nancy', 'ville' => 'Nancy', 'description' => "Description de l'Université de Nancy", 'site_internet' => null, 'telephone' => null],
            ['nom' => 'Université de Grenoble', 'ville' => 'Nancy', 'description' => "Description de l'Université de Grenoble", 'site_internet' => null, 'telephone' => null],
        ];

        foreach ($etablissements as $data) {
            $etablissement = new Etablissement();
            $etablissement->setNom($data['nom']);
            $etablissement->setVille($data['ville']);
            $etablissement->setDescription($data['description']);
            $etablissement->setSiteInternet($data['site_internet']);
            $etablissement->setTelephone($data['telephone']);
            $manager->persist($etablissement);
        }

        $manager->flush();
    }
}
