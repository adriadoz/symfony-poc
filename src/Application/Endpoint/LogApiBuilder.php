<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Application\Endpoint;

use G3\FrameworkPractice\Application\Log\LogSummaryBuilder;

final class LogApiBuilder
{
    private $logs;

    public function __construct(LogSummaryBuilder $logs)
    {
        $this->logs = $logs;
    }

    public function logSummaryFilter(array $filters): string
    {
        $levels = implode(',', $filters);

        return json_encode($this->logs->summaryLogFilter($levels));
    }

    public function logSummary()
    {
        return json_encode($this->logs->summaryLog());
    }
}
