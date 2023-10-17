<?php

namespace App\DataFixtures;

use App\Entity\MeanOfPayment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MeanOfPaymentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $labels = ['Check', 'transfer'];

        $position = 10;
        foreach ($labels as $label) {
            $status = new MeanOfPayment();
            $status->setPosition($position);
            $status->setLabel($label);
            $position += 10;
            $manager->persist($status);
        }

        $manager->flush();
    }
}
