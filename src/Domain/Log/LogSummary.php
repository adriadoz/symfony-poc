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

    public function summaryLogFilter(string $levels): array
    {
        return $this->logByLevels($this->toArray($levels));
    }

    public function summaryLog(): array
    {
        return $this->logByLevels();
    }

    private function logByLevels(array $levels = []): array
    {
        $summary = [];
        foreach ($this->logEntries->items() as $item) {
            $level = $item->levelName();
            if (in_array($level, $levels) || empty($levels)) {
                if (array_key_exists($level, $summary)) {
                    $summary[$level] = $summary[$level] + 1;
                } else {
                    $summary[$level] = 1;
                }
            }
        }

        if (empty($summary)) {
            return ['No log was found for the selected levels'];
        }

        return $summary;
    }

    private function toArray($levels): array
    {
        $lowCaseLevels = strtoupper($levels);
        $levels        = explode(",", $lowCaseLevels);

        return $levels;
    }
}