<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use Symfony\Component\EventDispatcher\Event;

final class LogEventDispatcher extends Event
{
    private $environment;

    public function __construct(String $environment)
    {
        $this->environment = $environment;
    }

    public function locallyRaised()
    {
        var_dump($this->environment);
        echo "Event log_record.locally_raised success!";
    }

    public function remotelyAdded()
    {
        echo "You added an error to log handler!";
    }
}
