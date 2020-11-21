<?php

namespace App\DataFixtures;

use App\Entity\CostType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CostTypeFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $arrStatusLabel = [
            [
                'en' => 'Bank',
                'fr' => 'Banque',
            ],
            [
                'en' => 'Taxes',
                'fr' => 'Impôts',
            ],
            [
                'en' => 'Supplies',
                'fr' => 'Fournitures',
            ],
        ];

        $position = 10;
        foreach ($arrStatusLabel as $label) {
            $status = new CostType();
            $status->setPosition($position);
            $status->setTrad($label);
            $position += 10;
            $manager->persist($status);
        }

        $manager->flush();
    }
}
