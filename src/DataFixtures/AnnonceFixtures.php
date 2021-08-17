<?php

namespace App\DataFixtures;

use App\Entity\AnnonceAutomobile;
use App\Entity\AnnonceEmploi;
use App\Entity\AnnonceImmobilier;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AnnonceFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        foreach (range(0,500) as $nombre) {
            $categorie = Categorie::CATEGORIES[array_rand(Categorie::CATEGORIES)];
            if($categorie === Categorie::EMPLOI)
            {
                $annonce = new AnnonceEmploi();
                $annonce->setContrat(AnnonceEmploi::CONTRATS[array_rand(AnnonceEmploi::CONTRATS)]);
                $annonce->setSalaire($this->faker->numberBetween(1400,4500));
            }
            if($categorie === Categorie::AUTOMOBILE)
            {
                $annonce = new AnnonceAutomobile();
                $marque = AnnonceAutomobile::MARQUES[array_rand(AnnonceAutomobile::MARQUES)];
                $modele = AnnonceAutomobile::MODELES[$marque][array_rand(AnnonceAutomobile::MODELES[$marque])];
                $annonce->setMarque($marque);
                $annonce->setModele($modele);
                $annonce->setCarburant(AnnonceAutomobile::CARBURANTS[array_rand(AnnonceAutomobile::CARBURANTS)]);
                $annonce->setPrix($this->faker->numberBetween(800,9000));
            }
            if($categorie === Categorie::IMMOBILIER)
            {
                $annonce = new AnnonceImmobilier();
                $annonce->setPrix($this->faker->numberBetween(300,2000));
                $annonce->setSurface($this->faker->numberBetween(25,500));
            }
            $annonce->setCategorie($this->getReference($categorie));
            $annonce->setUser($this->getReference($nombre%12+1));
            $annonce->setTitre($categorie.' : '.$this->faker->realText('25'));
            $annonce->setContenu($this->faker->realText());
            $manager->persist($annonce);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategorieFixtures::class,
        ];
    }
}
