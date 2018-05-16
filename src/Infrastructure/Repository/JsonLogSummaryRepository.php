<?php
declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;

class JsonLogSummaryRepository implements LogSummaryRepositoryInterface
{
    private const PATH = '../var/log/';
    private $levels = ['ERROR', 'WARNING', 'INFO', 'CRITICAL', 'DEBUG'];
    private $encoded;

    public function saveLogSummary(LogSummary $logSummary, String $environment)
    {
        $this->encoded = json_encode($logSummary->__invoke($this->levels),JSON_PRETTY_PRINT);
        $fp = fopen(SELF::PATH.$environment.'-summary.json', 'w');
        fwrite($fp, $this->encoded);
        fclose($fp);
    }

    public function getLogSummary(String $environment)
    {
        if(file_exists ( SELF::PATH.$environment.'-summary.json')){
            $json = file_get_contents(SELF::PATH.$environment.'-summary.json');
            return json_decode($json, true);
        }else{
            return null;
        }
    }
}