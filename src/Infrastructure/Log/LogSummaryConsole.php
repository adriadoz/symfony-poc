<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class LogSummaryConsole extends Command
{
    private $environment;

    public function __construct(string $environment)
    {
        parent::__construct();
        $this->environment = $environment;
    }

    protected function configure(): void
    {
        $this
            ->setName('log:summary')
            ->setDescription('Print summary of last 15 day log');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $logGetters = new LogSummaryGetter($this->environment);
        $logSummary = $logGetters->__invoke();

        $this->print($logSummary->__invoke(), $output);
    }

    private function print(array $summaryLog, OutputInterface $output): void
    {
        foreach ($summaryLog as $key => $value) {
            $output->writeln($key . ": " . $value);
        }

    }
}
