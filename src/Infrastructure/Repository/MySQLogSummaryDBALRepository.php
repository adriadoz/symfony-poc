<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Error;
use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class MySQLogSummaryDBALRepository extends Controller implements LogSummaryRepositoryInterface
{
    private $levels = ['ERROR', 'WARNING', 'INFO', 'CRITICAL', 'DEBUG'];
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function saveLogSummary(LogSummary $logSummary, String $environment)
    {
        $summary = $logSummary->__invoke($this->levels);

        $this->truncateTable();

        foreach ($summary as $level => $total) {
            $this->insertLog($level, $total, $environment);
        }
    }

    public function getLogSummary(String $environment)
    {
        $result = $this->selectSummaryOnDatabase($environment);

        if ($result) {
            $summary = [];
            foreach ($result as $log) {
                $summary[$log['level']] = $log['total'];
            }

            return $summary;
        }

        return null;
    }

    public function insertLog(string $levels, int $total, string $environment): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('log_summary')
            ->values(
                [
                    'level'       => ':level',
                    'total'       => ':total',
                    'environment' => ':environment',
                ]
            )
            ->setParameter(':level', $levels)
            ->setParameter(':total', $total)
            ->setParameter(':environment', $environment);

        $queryBuilder->execute();
    }

    private function selectSummaryOnDatabase(string $environment): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $parameters = [
            'environment' => $environment,
        ];

        $queryBuilder->select('level, total, environment');
        $queryBuilder->from('log_summary');
        $queryBuilder->where('environment = :environment');
        $queryBuilder->setParameters($parameters);
        $stmt  = $queryBuilder->execute();
        $query = $stmt->fetchAll();

        return $query;
    }

    private function truncateTable(): void
    {
        try {
            $platform = $this->connection->getDatabasePlatform();
            $this->connection->executeUpdate($platform->getTruncateTableSQL('log_summary', true));
        } catch (DBALException $e) {
            throw new Error('Error on truncate database');
        }
    }
}
