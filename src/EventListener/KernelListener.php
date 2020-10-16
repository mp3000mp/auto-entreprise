<?php
	
	namespace App\EventListener;
	
	use Symfony\Component\HttpKernel\Event\RequestEvent;
	
	
	/**
	 * Class KernelListener
	 * @package App\EventListener
	 */
	class KernelListener {
		
		
		/**
		 * se lance avant l'authentication
		 *
		 * @param RequestEvent $event
		 */
		public function onKernelRequestPreAuth( RequestEvent $event ) {
			
			// get request
			$request = $event->getRequest();
			$session = $request->getSession();
			$cookie = $request->cookies;

			// gestion de la locale
			// si locale dans session on prend, si dans cookie on prend, sinon : en
			if ( $session->has( 'locale' ) ) {
				$locale = $session->get( '_locale' );
			} elseif ( $cookie->has( 'locale' ) ) {
				$locale = $cookie->get( 'locale' );
				$session->set('_locale', $locale);
			} else {
				$locale = 'en';
				$session->set('_locale', $locale);
			}
			$request->setLocale( $locale );
		}
	}
