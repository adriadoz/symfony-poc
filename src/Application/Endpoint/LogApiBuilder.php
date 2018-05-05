<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Application\Endpoint;

use G3\FrameworkPractice\Application\Log\LogSummary;

final class LogApiBuilder
{
    private $logs;

    public function __construct(LogSummary $logs)
    {
        $this->logs = $logs;
    }

    public function __invoke(): string
    {
        return json_encode($this->logs->summaryLog());
    }

}
