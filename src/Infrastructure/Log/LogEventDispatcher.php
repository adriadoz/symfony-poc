<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use Symfony\Component\EventDispatcher\Event;

final class LogEventDispatcher extends Event
{
    private $eventName;

    public function __construct(string $eventName)
    {
        $this->eventName = $eventName;
    }

    public function locallyRaised()
    {
        return "Event " . $this->eventName . " success!";
    }
}
