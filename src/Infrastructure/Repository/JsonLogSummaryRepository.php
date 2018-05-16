<?php
declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;

class JsonLogSummaryRepository implements LogSummaryRepositoryInterface
{
    private $levels = ['error', 'warning', 'info', 'critical', 'debug'];
    private $encoded;
    public function saveLogSummary(LogSummary $logSummary)
    {
        $this->encoded = json_encode($logSummary->__invoke($this->levels));
        var_dump($this->encoded);
    }
}