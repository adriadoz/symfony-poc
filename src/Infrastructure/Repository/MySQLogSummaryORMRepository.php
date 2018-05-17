<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;

final class MySQLogSummaryORMRepository implements LogSummaryRepositoryInterface
{
    public function saveLogSummary(LogSummary $logSummary, String $environment)
    {
        // @todo Implement the fucking saveLogSummary() method!!!
    }

    public function getLogSummary(String $environment)
    {
        // @todo Implement the fucking getLogSummary() method!!!
    }
}
