<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\UserEntity;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedEvent extends Event
{
    private $newUser;

    public function __construct(UserEntity $newUser = null)
    {
        $this->userEntity = $newUser;
    }

    public function getUser(): ?UserEntity
    {
        return $this->userEntity;
    }
}
