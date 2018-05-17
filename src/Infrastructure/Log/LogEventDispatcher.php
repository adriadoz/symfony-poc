<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use Doctrine\DBAL\Connection;
use G3\FrameworkPractice\Application\Log\LogSummaryCalculator;
use G3\FrameworkPractice\Infrastructure\Repository\MySQLogSummaryDBALRepository;

final class LogEventDispatcher
{
    private const PATH = "../var/log/";
    private $environment;
    private $connection;

    public function __construct(String $environment, Connection $connection)
    {
        $this->environment = $environment;
        $this->connection = $connection;
    }

    public function __invoke()
    {
        $logSummaryCalculator = new LogSummaryCalculator($this->environment, self::PATH);
        $logSummary           = $logSummaryCalculator->__invoke();
        $repo                 = new MySQLogSummaryDBALRepository($this->connection);
        $repo->saveLogSummary($logSummary, $this->environment);
    }
}
