<?php
declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;

class JsonLogSummaryRepository implements LogSummaryRepositoryInterface
{
    private $path;
    private $levels = ['ERROR', 'WARNING', 'INFO', 'CRITICAL', 'DEBUG'];
    private $encoded;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function saveLogSummary(LogSummary $logSummary, String $environment)
    {
        $this->encoded = json_encode($logSummary->__invoke($this->levels),JSON_PRETTY_PRINT);
        $fp = fopen($this->path.$environment.'-summary.json', 'w');
        fwrite($fp, $this->encoded);
        fclose($fp);
    }

    public function getLogSummary(String $environment)
    {
        if(file_exists ( $this->path.$environment.'-summary.json')){
            $json = file_get_contents($this->path.$environment.'-summary.json');
            return json_decode($json, true);
        }else{
            return null;
        }
    }
}