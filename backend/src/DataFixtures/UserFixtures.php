<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserFixtures extends Fixture
{
    private PasswordHasherInterface $passwordHasher;

    public function __construct(PasswordHasherFactoryInterface $hasherFactory)
    {
        $this->passwordHasher = $hasherFactory->getPasswordHasher(User::class);
    }

    public function load(ObjectManager $manager): void
    {
        $password = 'Test2000!';

        $user = new User();
        $encodedPassword = $this->passwordHasher->hash($password);
        $user->setEmail('test@mp3000mp.fr');
        $user->setUsername('mp3000');
        $user->setPassword($encodedPassword);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }
}
