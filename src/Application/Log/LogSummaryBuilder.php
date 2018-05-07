<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Application\Log;

use G3\FrameworkPractice\Domain\Log\LogEntryCollection;
use G3\FrameworkPractice\Domain\Log\LogSummary;

final class LogSummaryBuilder
{
    private $content;

    public function __construct(LogEntryCollection $content)
    {
        $this->content = $content;
    }

    public function summaryLog(): array{
        $logSummary = new LogSummary($this->content);
        return $logSummary->summaryLog();
    }

    public function summaryLogFilter(string $levels): array{
        $logSummary = new LogSummary($this->content);
        return $logSummary->summaryLogFilter($levels);
    }
}
