<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
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
        $contrat = ['CDI','CDD','INTERIM','STAGE'];
        $carburant = ['Essence','Diesel','GPL','Sans Plomb 95'];
        foreach (range(0,100) as $nombre) {
            $annonce = new Annonce();
            $categorie = Categorie::CATEGORIES[array_rand(Categorie::CATEGORIES)];
            $annonce->setCategorie($this->getReference($categorie));
            if($categorie === Categorie::EMPLOI)
            {
                $annonce->setContrat($contrat[array_rand($contrat)]);
                $annonce->setSalaire($this->faker->numberBetween(1400,4500));
            }
            if($categorie === Categorie::AUTOMOBILE)
            {
                $annonce->setCarburant($carburant[array_rand($carburant)]);
                $annonce->setPrix($this->faker->numberBetween(800,9000));
            }
            if($categorie === Categorie::IMMOBILIER)
            {
                $annonce->setPrix($this->faker->numberBetween(300,2000));
                $annonce->setSurface($this->faker->numberBetween(25,500));
            }
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
