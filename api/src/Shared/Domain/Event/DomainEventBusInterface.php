<?php

namespace App\Shared\Domain\Event;

interface DomainEventBusInterface
{
    public function dispatch(EventInterface ...$event): void;
}