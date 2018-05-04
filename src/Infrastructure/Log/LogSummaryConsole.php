<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use G3\FrameworkPractice\Domain\Log\LogEntryCollection;
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
            ->setDescription('Print last 15 day log file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $logsToShow = new LogSummaryGetter($this->environment);
        $this->print($output, $this->logToJson($logsToShow->__invoke()));
    }

    private function print(OutputInterface $output, string $textToPrint): void
    {
        $output->writeln([$textToPrint]);
    }

    private function logToJson(LogEntryCollection $collection): string
    {
        $logs = [];
        foreach($collection->items() as $item) {
            $log['message'] = $item->message();
            $log['channel'] = $item->channel();
            $log['levelName'] = $item->levelName();
            array_push($logs, $log);
        }

        return json_encode($logs);
    }
}
