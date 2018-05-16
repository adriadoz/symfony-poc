<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use G3\FrameworkPractice\Application\Log\LogSummaryCalculator;
use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogSummaryRepositoryInterface;


final class LogSummaryGetter
{
    private $environment;
    private $summaryRepository;
    private $summaryCalculator;

    public function __construct( string $environment, LogSummaryRepositoryInterface $summaryRepository, LogSummaryCalculator $summaryCalculator)
    {
        $this->environment = $environment;
        $this->summaryRepository = $summaryRepository;
        $this->summaryCalculator = $summaryCalculator;
    }

    public function __invoke(): LogSummary
    {
        $inMemory = $this->summaryRepository->getLogSummary($this->environment);
        if(is_null($inMemory)){
            echo 'calculated';

            return $this->summaryCalculator->__invoke();
        }else{
            $logSummary = new LogSummary();
            $logSummary->addSummary($inMemory);
            echo 'inmemory';
            return $logSummary;
        }
    }
}
