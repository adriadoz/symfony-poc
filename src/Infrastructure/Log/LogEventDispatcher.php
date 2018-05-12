<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use Symfony\Component\EventDispatcher\Event;

final class LogEventDispatcher extends Event
{
    public function locallyRaised(): string
    {
        echo "Event log_record.locally_raised success!";
    }

    public function remotelyAdded(): string
    {
        echo "You added an error to log handler!";
    }
}
