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

    public function __invoke(): array
    {
        return $this->logByLevels();
    }

    private function logByLevels(): array
    {
        $summary = [];
        foreach ($this->content->items() as $item) {
            $level = $item->levelName();
            if (array_key_exists($level, $summary)) {
                $summary[$level] = $summary[$level] + 1;
            } else {
                $summary[$level] = 1;
            }
        }

        return $summary;
    }
}
