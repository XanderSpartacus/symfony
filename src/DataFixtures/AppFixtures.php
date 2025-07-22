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
            ['nom' => 'Université de Marseille', 'ville' => 'Marseille', 'description' => "Description de l'Université de Marseille"],
            ['nom' => 'Université de Lille', 'ville' => 'Lille', 'description' => "Description de l'Université de Lille"],
            ['nom' => 'Université de Pau', 'ville' => 'Pau', 'description' => "Description de l'Université de Pau"],
            ['nom' => 'Université de Rennes', 'ville' => 'Rennes', 'description' => "Description de l'Université de Rennes"],
            ['nom' => 'Université de Nantes', 'ville' => 'Nantes', 'description' => "Description de l'Université de Nantes"],
            ['nom' => 'Université de Bordeaux', 'ville' => 'Bordeaux', 'description' => "Description de l'Université de Bordeaux"],
            ['nom' => 'Université de Lyon', 'ville' => 'Lyon', 'description' => "Description de l'Université de Lyon"],
            ['nom' => 'Université de Nice', 'ville' => 'Nice', 'description' => "Description de l'Université de Nice"],
            ['nom' => 'Université de Paris', 'ville' => 'Paris', 'description' => "Description de l'Université de Paris"],
            ['nom' => 'Université de Rouen', 'ville' => 'Rouen', 'description' => "Description de l'Université de Rouen"],
            ['nom' => 'Université de Mulhouse', 'ville' => 'Mulhouse', 'description' => "Description de l'Université de Mulhouse"],
            ['nom' => 'Université de Nancy', 'ville' => 'Nancy', 'description' => "Description de l'Université de Nancy"],
            ['nom' => 'Université de Grenoble', 'ville' => 'Nancy', 'description' => "Description de l'Université de Grenoble"],
        ];

        foreach ($etablissements as $data) {
            $etablissement = new Etablissement();
            $etablissement->setNom($data['nom']);
            $etablissement->setVille($data['ville']);
            $etablissement->setDescription($data['description']);
            $manager->persist($etablissement);
        }

        $manager->flush();
    }
}
