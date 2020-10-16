<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{


    /**
     * @var PasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
	{
	    $this->encoder = $encoder;
	}



    public function load(ObjectManager $manager)
    {

        $password = 'Test2000!';

		$user = new User();
        $encodedPassword = $this->encoder->encodePassword($user, $password);
		$user->setIsActive(true);
		$user->setNbFailedConnexion(0);
		$user->setEmail('mperret@mp3000mp.fr');
		$user->setFirstName('Matthias');
		$user->setLastName('Perret');
		$user->setPassword($encodedPassword);
		$user->setRoles(['ROLE_USER','ROLE_ADMIN']);

		$manager->persist($user);

        $manager->flush();

    }

}
