<?php

namespace App\EventListener;

    use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
    use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

    /**
     * Class SecurityListener.
     */
    class SecurityListener
    {
        /**
         * SecurityListener constructor.
         */
        public function __construct()
        {
        }

        /**
         * si echec.
         */
        public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
        {
            // normalement impossible d'arriver ici
        }

        /**
         * si success.
         */
        public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
        {
        }
    }
