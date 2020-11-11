<?php

namespace App\DataFixtures;

use App\Entity\TenderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TenderStatusFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $arrStatusLabel = [
            [
                'en' => 'Ongoing',
                'fr' => 'En cours',
            ],
            [
                'en' => 'Sent',
                'fr' => 'Envoyé',
            ],
            [
                'en' => 'Accepted',
                'fr' => 'Accepté',
            ],
            [
                'en' => 'Refused',
                'fr' => 'Refusé',
            ],
            [
                'en' => 'Canceled',
                'fr' => 'Annulé',
            ],
        ];

        $position = 10;
        foreach ($arrStatusLabel as $label) {
            $status = new TenderStatus();
            $status->setPosition($position);
            $status->setTrad($label);
            $position += 10;
            $manager->persist($status);
        }

        $manager->flush();
    }
}
