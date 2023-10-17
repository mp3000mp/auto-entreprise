<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private PasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $password = 'Test2000!';

        $user = new User();
        $encodedPassword = $this->hasher->hash($password);
        $user->setEmail('test@mp3000mp.fr');
        $user->setUsername('mp3000');
        $user->setPassword($encodedPassword);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }
}
