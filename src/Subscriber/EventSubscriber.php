<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Event\UserCreatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EventSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [UserCreatedEvent::class => 'onUserCreatedEvent'];
    }

    public function onUserCreatedEvent(UserCreatedEvent $event): void
    {
        $user = $event->getUser();
        $this->logger->info(
            sprintf(
                'New user has been created | firstName : %s | lastName :  %s | email : %s',
                $user->getEmail(),
                $user->getFirstName(),
                $user->getLastName()
            )
        );
    }
}
