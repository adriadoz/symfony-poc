<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log;

final class LogSummary
{
    private $logEntries;

    public function __construct(LogEntryCollection $content)
    {
        $this->logEntries = $content;
    }

    public function __invoke(array $levels): array
    {
        return $this->logByLevels($levels);
    }

    private function logByLevels(array $levels): array
    {
        $summary = [];
        foreach ($this->logEntries->items() as $item) {
            $summary = $this->increaseLogLevels($levels, $item, $summary);
        }

        if (empty($summary)) {
            return ['No log was found for the selected levels'];
        }

        return $summary;
    }

    private function increaseLogLevels(array $levels, LogEntry $item, array $summary): array
    {
        $level = $item->levelName();

        if (in_array($level, $levels) || empty($levels)) {
            if (array_key_exists($level, $summary)) {
                $summary[$level] = $summary[$level] + 1;
            } else {
                $summary[$level] = 1;
            }
        }

        return $summary;
    }
}
