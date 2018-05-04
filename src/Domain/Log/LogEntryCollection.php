<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log;

final class LogEntryCollection
{
    private $collection = [];

    public function add(LogEntry $logEntry)
    {
        array_push($this->collection, $logEntry);
    }

    public function items(): array
    {
        return $this->collection;
    }
}
