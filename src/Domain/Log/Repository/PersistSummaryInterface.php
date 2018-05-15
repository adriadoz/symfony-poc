<?php
declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log\Repository;

use G3\FrameworkPractice\Domain\Log\LogSummary;

interface PersistSummaryInterface
{
    public function saveLogSummary(LogSummary $logSummary);
}