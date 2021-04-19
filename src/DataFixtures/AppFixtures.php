<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Mp3000mp\TOSBundle\Entity\TermsOfService;

class AppFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $tos = new TermsOfService();
        $tos->setPublishedAt(new \DateTime());

        $manager->persist($tos);
        $manager->flush();
    }
}
