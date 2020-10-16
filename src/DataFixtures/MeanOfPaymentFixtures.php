<?php

namespace App\DataFixtures;

use App\Entity\MeanOfPayment;
use App\Entity\TenderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class MeanOfPaymentFixtures extends Fixture
{


	public function __construct()
	{

	}

    public function load(ObjectManager $manager)
    {

    	$arrMOPLabel = [
    		[
    		    'en' => 'Check',
                'fr' => 'Chèque',
            ],
            [
                'en' => 'Transfer',
                'fr' => 'Virement',
            ],
	    ];

    	$position = 10;
        foreach ($arrMOPLabel as $label) {
            $MOP = new MeanOfPayment();
	        $MOP->setTrad($label);
	        $MOP->setPosition($position);
            $manager->persist($MOP);
            $position += 10;
        }

        $manager->flush();

    }
}
