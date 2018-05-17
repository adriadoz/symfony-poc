<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use G3\FrameworkPractice\Application\Log\LogSummaryCalculator;
use G3\FrameworkPractice\Infrastructure\Repository\JsonLogSummaryRepository;

final class LogEventDispatcher
{
    private const PATH = "../var/log/";
    private $environment;

    public function __construct(String $environment)
    {
        $this->environment = $environment;
    }

    public function __invoke()
    {
        $logSummaryCalculator = new LogSummaryCalculator($this->environment, self::PATH);
        $logSummary           = $logSummaryCalculator->__invoke();
        $repo                 = new JsonLogSummaryRepository(self::PATH);
        $repo->saveLogSummary($logSummary, $this->environment);
    }
}
