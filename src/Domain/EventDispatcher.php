<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain;

use Composer\EventDispatcher\Event;

interface EventDispatcher
{
    public function addListener($eventName, $listener, $priority = 0);

    public function dispatch($eventName, Event $event = null);
}
