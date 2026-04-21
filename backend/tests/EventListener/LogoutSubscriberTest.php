<?php

declare(strict_types=1);

namespace App\Tests\EventListener;

use App\EventListener\LogoutSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            [LogoutEvent::class => 'onLogout'],
            LogoutSubscriber::getSubscribedEvents()
        );
    }

    public function testOnLogoutSetsJsonResponse(): void
    {
        $event = new LogoutEvent(new Request(), null);
        $subscriber = new LogoutSubscriber();
        $subscriber->onLogout($event);

        $response = $event->getResponse();
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(['message' => 'Goodbye!'], json_decode($response->getContent(), true));
    }
}
