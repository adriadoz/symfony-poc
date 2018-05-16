<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log;

final class LogSummary
{
    private $logEntries;
    private $summary;

    public function addCollection(LogEntryCollection $content){
        $this->logEntries = $content;
        $this->processCollection();
    }

    public function addSummary($summary){
        $this->summary = $summary;
    }

    public function __invoke(array $levels): array
    {
        return $this->summary;

    }

    private function processCollection(): void
    {
        $this->summary = [];
        foreach ($this->logEntries->items() as $item) {
            $this->increaseLogLevels($item);
        }

        if (empty($this->summary)) {
            throw new \Error('No logs to sum');
        }
    }

    private function increaseLogLevels(LogEntry $item): void
    {
        $level = $item->levelName();
        if (array_key_exists($level, $this->summary)) {
            $this->summary[$level] = $this->summary[$level] + 1;
        } else {
            $this->summary[$level] = 1;
        }
    }

    public function filterByLevels(array $levels)
    {
        $filtered = [];
        while ($level = current($this->summary)) {
            if (in_array(key($this->summary), $levels) || empty($levels)) {
                $filtered[key($this->summary)] =  $level;
            }
            next($this->summary);
        }
        return $filtered;
    }
}
