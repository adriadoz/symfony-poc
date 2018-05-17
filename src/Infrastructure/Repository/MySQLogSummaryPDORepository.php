<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;
use PDO;
use PDOStatement;

final class MySQLogSummaryPDORepository implements LogSummaryRepositoryInterface
{
    private $levels = ['ERROR', 'WARNING', 'INFO', 'CRITICAL', 'DEBUG'];
    private $db_connect;

    public function __construct()
    {
        $this->db_connect = new PDO('mysql:host=localhost;dbname=frameworks', 'vagrant', 'vagrant');
        $this->db_connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $query = "INSERT INTO log_summary (level, total, environment) VALUES (:level, :total, :environment)";
        $stmt  = $this->onPrepare($query);

        $stmt->bindParam(':level', $levels, PDO::PARAM_STR);
        $stmt->bindParam(':total', $total, PDO::PARAM_INT);
        $stmt->bindParam(':environment', $environment, PDO::PARAM_STR);

        $stmt->execute();
    }

    private function onPrepare($query): PDOStatement
    {
        return $this->db_connect->prepare($query);
    }

    private function selectSummaryOnDatabase(string $environment): array
    {
        $query = "SELECT level, total, environment FROM log_summary WHERE environment = :environment";
        $stmt  = $this->onPrepare($query);

        $stmt->bindParam(':environment', $environment, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    private function truncateTable(): void
    {
        $query = "TRUNCATE TABLE log_summary";
        $stmt  = $this->onPrepare($query);
        $stmt->execute();
    }
}
