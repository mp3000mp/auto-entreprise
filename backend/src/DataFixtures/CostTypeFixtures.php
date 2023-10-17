<?php

namespace App\DataFixtures;

use App\Entity\CostType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CostTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $labels = ['Bank', 'Taxes', 'Supplies'];

        $position = 10;
        foreach ($labels as $label) {
            $status = new CostType();
            $status->setPosition($position);
            $status->setLabel($label);
            $position += 10;
            $manager->persist($status);
        }

        $manager->flush();
    }
}
