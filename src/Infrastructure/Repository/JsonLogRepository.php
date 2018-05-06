<?php
declare(strict_types=1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use G3\FrameworkPractice\Domain\Log\LogEntry;
use G3\FrameworkPractice\Domain\Log\Repository\LogRepositoryInterface;
use Monolog\Logger;

final class JsonLogRepository implements LogRepositoryInterface
{
    public function saveLog(Logger $logger, LogEntry $log): void
    {
        $message = $log->message();
        switch ($log->levelName()) {
            case 'DEBUG':
                $logger->debug($message);
                break;
            case 'WARNING':
                $logger->warning($message);
                break;
            case 'CRITICAL':
                $logger->critical($message);
                break;
            case 'ERROR':
                $logger->error($message);
                break;
            default:
                $logger->info($message);
        }
    }
}