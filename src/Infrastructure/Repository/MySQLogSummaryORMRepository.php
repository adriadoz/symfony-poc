<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;
use G3\FrameworkPractice\Infrastructure\Doctrine\Entity\LogSummaryEntry;

final class MySQLogSummaryORMRepository implements LogSummaryRepositoryInterface
{
    private $levels = ['ERROR', 'WARNING', 'INFO', 'CRITICAL', 'DEBUG'];
    private $em;
    private $connection;

    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->em = $em;
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

    private function selectSummaryOnDatabase(string $environment): array
    {

    }

    public function insertLog(string $levels, int $total, string $environment): void
    {
        $logSummaryEntry = new LogSummaryEntry($levels, $total, $environment);

        $this->em->persist($logSummaryEntry);
        $this->em->flush();
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
