<?php
declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Repository;

use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;

class JsonLogSummaryRepository implements LogSummaryRepositoryInterface
{
    private $levels = ['error', 'warning', 'info', 'critical', 'debug'];
    private $encoded;
    public function saveLogSummary(LogSummary $logSummary, String $environment)
    {
        $this->encoded = json_encode($logSummary->__invoke($this->levels),JSON_PRETTY_PRINT);
        $fp = fopen('var/summary/'.$environment.'.json', 'w');
        fwrite($fp, $this->encoded);
        fclose($fp);
    }

    public function getLogSummary(String $environment){
        //TODO search files
    }
}