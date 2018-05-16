<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log;

final class LogSummary
{
    private $logEntries;
    private $summary;

    public function addCollection(LogEntryCollection $content){
        $this->logEntries = $content;
    }

    public function addSummary($summary){
        $this->summary = $summary;
    }

    public function __invoke(array $levels): array
    {
        $this->logByLevels($levels);
        return $this->summary;
    }

    private function logByLevels(array $levels): void
    {
        if(isset($this->summary)){
            $copy = $this->summary;
        }else {
            $this->summary = [];
            foreach ($this->logEntries->items() as $item) {
                $this->increaseLogLevels($levels, $item);
            }

            if (empty($this->summary)) {
                throw new \Error('No logs to sum');
            }
        }
    }

    private function increaseLogLevels(array $levels, LogEntry $item): void
    {
        $level = $item->levelName();

        if (in_array($level, $levels) || empty($levels)) {
            if (array_key_exists($level, $this->summary)) {
                $this->summary[$level] = $this->summary[$level] + 1;
            } else {
                $this->summary[$level] = 1;
            }
        }
    }
}
