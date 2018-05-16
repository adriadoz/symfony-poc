<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use G3\FrameworkPractice\Application\Log\LogSummaryCalculator;
use G3\FrameworkPractice\Infrastructure\Repository\JsonLogSummaryRepository;
use Symfony\Component\EventDispatcher\Event;

final class LogEventDispatcher extends Event
{
    private const PATH = "../var/log/";
    private $environment;

    public function __construct(String $environment)
    {
        $this->environment = $environment;
    }

    public function locallyRaised()
    {
        $this->updateLogSummaryInMemory();
    }

    public function remotelyAdded()
    {
        $this->updateLogSummaryInMemory();
    }

    private function updateLogSummaryInMemory(){
        $logSummaryCalculator = new LogSummaryCalculator($this->environment, SELF::PATH);
        $logSummary = $logSummaryCalculator->__invoke();
        $repo = new JsonLogSummaryRepository(SELF::PATH);
        $repo->saveLogSummary($logSummary,  $this->environment);
    }
}
