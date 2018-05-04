<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log;

final class LogEntry
{
    private $message;
    private $channel;
    private $levelName;

    public function __construct(string $message, string $channel, string $levelName)
    {
        $this->message   = $message;
        $this->channel   = $channel;
        $this->levelName = $levelName;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function channel(): string
    {
        return $this->channel;
    }

    public function levelName(): string
    {
        return $this->levelName;
    }
}
