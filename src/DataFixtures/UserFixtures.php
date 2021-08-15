<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        foreach (range(0, 12) as $number) {
            $user = new User();
            $user->setEmail("test{$number}@test.test");
            $user->setPassword($this->passwordHasher->hashPassword($user,'test'));
            $this->addReference($number, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
