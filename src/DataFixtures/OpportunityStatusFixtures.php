<?php

namespace App\DataFixtures;

use App\Entity\OpportunityStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class OpportunityStatusFixtures extends Fixture
{

	public function __construct()
	{

	}

    public function load(ObjectManager $manager)
    {

    	$arrStatusLabel = [
            [
                'en' => 'Track',
                'fr' => 'Piste',
            ],
            [
                'en' => 'Expr. besoin ongoing',
                'fr' => 'Expr. besoin en cours',
            ],
            [
                'en' => 'Expr. besoin sent',
                'fr' => 'Expr. besoin en envoyée',
            ],
            [
                'en' => 'Tender ongoing',
                'fr' => 'Devis en cours',
            ],
            [
                'en' => 'Tender sent',
                'fr' => 'Devis envoyé',
            ],
            [
                'en' => 'Dev ongoing',
                'fr' => 'Dev en cours',
            ],
            [
                'en' => 'Recette',
                'fr' => 'Recette',
            ],
            [
                'en' => 'Bill sent',
                'fr' => 'Facture envoyée',
            ],
            [
                'en' => 'Payed',
                'fr' => 'Payé',
            ],
            [
                'en' => 'Canceled',
                'fr' => 'Annulé',
            ],
	    ];

        $position = 10;
        foreach ($arrStatusLabel as $label) {
            $status = new OpportunityStatus();
            $status->setPosition($position);
            $status->setTrad($label);
            $position += 10;
            $manager->persist($status);
        }

        $manager->flush();

    }
}
