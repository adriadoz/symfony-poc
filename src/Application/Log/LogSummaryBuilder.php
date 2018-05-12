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

    public function __invoke(array $levels = []): array
    {
        $logSummary = new LogSummary($this->content);

        return $logSummary->__invoke($levels);
    }
}
