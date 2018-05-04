<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Application\Log;

use G3\FrameworkPractice\Domain\Log\LogEntryCollection;

final class LogSummary
{
    private $content;

    public function __construct(LogEntryCollection $content)
    {
        $this->content = $content;
    }

    public function __invoke(string $levels): array
    {
        return $this->logByLevels($this->toArray($levels));
    }

    public function logByLevels(array $levels): array
    {
        $summary = [];
        foreach ($this->content->items() as $item) {
            $level = $item->levelName();
            if(in_array($level, $levels)) {
                if (array_key_exists($level, $summary)) {
                    $summary[$level] = $summary[$level] + 1;
                } else {
                    $summary[$level] = 1;
                }
            }
        }

        if(empty($summary)) {
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
