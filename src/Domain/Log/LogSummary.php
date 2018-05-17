<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log;

final class LogSummary
{
    private $logEntries;
    private $summaryAsArray;
    private $info;
    private $critical;
    private $warning;
    private $error;
    private $debug;

    public function __construct()
    {
        $this->info     = 0;
        $this->critical = 0;
        $this->warning  = 0;
        $this->error    = 0;
        $this->debug    = 0;
    }

    public function addCollection(LogEntryCollection $content)
    {
        $this->logEntries = $content;
        $this->processCollection();
        $this->getSummaryInVariables();
    }

    public function addSummary($summary)
    {
        $this->summaryAsArray = $summary;
        $this->getSummaryInVariables();
    }

    public function __invoke(array $levels): array
    {
        return $this->summaryAsArray;
    }

    public function filterByLevels(array $levels)
    {
        $filtered = [];
        reset($this->summaryAsArray);
        while ($level = current($this->summaryAsArray)) {
            if (in_array(key($this->summaryAsArray), $levels) || empty($levels)) {
                $filtered[key($this->summaryAsArray)] = $level;
            }
            next($this->summaryAsArray);
        }

        return $filtered;
    }

    public function getInfo(): int
    {
        return $this->info;
    }

    public function getCritical(): int
    {
        return $this->critical;
    }

    public function getWarning(): int
    {
        return $this->warning;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getDebug(): int
    {
        return $this->debug;
    }

    private function processCollection(): void
    {
        $this->summaryAsArray = [];
        foreach ($this->logEntries->items() as $item) {
            $this->increaseLogLevels($item);
        }

        if (empty($this->summaryAsArray)) {
            throw new \Error('No logs to sum');
        }
    }

    private function increaseLogLevels(LogEntry $item): void
    {
        $level = $item->levelName();
        if (array_key_exists($level, $this->summaryAsArray)) {
            $this->summaryAsArray[$level] = $this->summaryAsArray[$level] + 1;
        } else {
            $this->summaryAsArray[$level] = 1;
        }
    }

    private function getSummaryInVariables()
    {
        reset($this->summaryAsArray);
        while ($level = current($this->summaryAsArray)) {
            switch (key($this->summaryAsArray)) {
                case "INFO":
                    $this->info = $level;
                    break;
                case "CRITICAL":
                    $this->critical = $level;
                    break;
                case "WARNING":
                    $this->warning = $level;
                    break;
                case "ERROR":
                    $this->error = $level;
                    break;
                case "DEBUG":
                    $this->debug = $level;
                    break;
            }
            next($this->summaryAsArray);
        }
    }
}
