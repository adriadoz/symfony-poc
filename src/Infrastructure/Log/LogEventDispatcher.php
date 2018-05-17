<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use G3\FrameworkPractice\Application\Log\LogSummaryCalculator;
use G3\FrameworkPractice\Infrastructure\Repository\MySQLogSummaryORMRepository;

final class LogEventDispatcher
{
    private const PATH = "../var/log/";
    private $environment;
    private $connection;
    /** @var EntityManager */
    private $entityManager;

    public function __construct(String $environment, Connection $connection, EntityManager $entityManager)
    {
        $this->environment = $environment;
        $this->connection = $connection;
        $this->entityManager = $entityManager;
    }

    public function __invoke()
    {
        $logSummaryCalculator = new LogSummaryCalculator($this->environment, self::PATH);
        $logSummary           = $logSummaryCalculator->__invoke();
        $repo                 = new MySQLogSummaryORMRepository($this->connection, $this->entityManager);
        $repo->saveLogSummary($logSummary, $this->environment);
    }
}
