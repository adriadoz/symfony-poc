<?php
declare(strict_types=1);

namespace G3\FrameworkPractice\Domain\Log\Repository;

use G3\FrameworkPractice\Domain\Log\LogEntry;
use Monolog\Logger;

interface LogRepositoryInterface
{
    public function saveLog(Logger $logger, LogEntry $type);
}