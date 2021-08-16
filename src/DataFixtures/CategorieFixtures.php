<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (Categorie::CATEGORIES as $k=>$categorie_name) {
            $categorie = new Categorie();
            $categorie->setName($categorie_name);
            $categorie->setId($k);
            $this->addReference($categorie_name, $categorie);
            $manager->persist($categorie);
        }
        $manager->flush();
    }
}
