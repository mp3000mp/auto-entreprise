<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface {


	/**
	 * UserChecker constructor.
	 *
	 */
	public function __construct() {

	}

	/**
	 * @param UserInterface $user
	 */
	public function checkPreAuth( UserInterface $user ) {
		if ( ! $user instanceof User ) {
			return;
		}
	}

	/**
	 * @param UserInterface $user
	 */
	public function checkPostAuth( UserInterface $user ) {
		if ( ! $user instanceof User ) {
			return;
		}
	}
}
