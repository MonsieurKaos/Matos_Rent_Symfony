<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setMatricule('MAT-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT)); // Exemple : MAT-0001, MAT-0002, ...
            $user->setNom('User' . $i);
            $user->setPrenom('FirstName' . $i);
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword('password');
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
