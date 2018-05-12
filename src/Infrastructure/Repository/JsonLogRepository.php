<?php
declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use G3\FrameworkPractice\Domain\Log\LogEntry;
use G3\FrameworkPractice\Domain\Log\Repository\LogRepositoryInterface;
use G3\FrameworkPractice\Domain\Log\ValueObjects\LogLevelName;
use Monolog\Logger;

final class JsonLogRepository implements LogRepositoryInterface
{
    public function saveLog(Logger $logger, LogEntry $log): void
    {
        $message = $log->message();
        switch ($log->levelName()) {
            case LogLevelName::Debug():
                $logger->debug($message);
                break;
            case LogLevelName::Warning():
                $logger->warning($message);
                break;
            case LogLevelName::Critical():
                $logger->critical($message);
                break;
            case LogLevelName::Error():
                $logger->error($message);
                break;
            default:
                $logger->info($message);
        }
    }
}
