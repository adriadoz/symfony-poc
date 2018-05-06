<?php
declare(strict_types=1);

namespace G3\FrameworkPractice\Domain\Log\Repository;

use G3\FrameworkPractice\Domain\Log\LogEntry;
use G3\FrameworkPractice\Types\ValueObject\Level;
use Monolog\Logger;

interface LogRepositoryInterface
{
    public function saveLog(Logger $logger, LogEntry $type);
}