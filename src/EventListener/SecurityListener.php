<?php
	
	namespace App\EventListener;
	
	use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
	use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
	
	
	/**
	 * Class SecurityListener
	 * @package App\EventListener
	 */
	class SecurityListener {
		
		protected $em;
		
		/**
		 * SecurityListener constructor.
		 *
		 */
		public function __construct()
		{
		
		}
		
		
		/**
		 * si echec
		 * @param AuthenticationFailureEvent $event
		 */
		public function onAuthenticationFailure( AuthenticationFailureEvent $event )
		{
			
			// normalement impossible d'arriver ici
			
		}
		
		
		/**
		 * si success
		 * @param InteractiveLoginEvent $event
		 */
		public function onSecurityInteractiveLogin( InteractiveLoginEvent $event )
		{
		
		}
	}
