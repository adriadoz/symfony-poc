<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class LogSummaryConsole extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('log:summary')
            ->setDescription('Print summary of last 15 day log')
            ->addArgument(
                'environment',
                InputArgument::OPTIONAL,
                '[string] Enter a environment to show. Example: "dev"'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $environment = $this->getEnvironment($input, $output);
        $logGetters  = new LogSummaryGetter($environment);
        $logSummary  = $logGetters->__invoke();

        $this->print($logSummary->__invoke(), $output);
    }

    private function getEnvironment(InputInterface $input, OutputInterface $output): string
    {
        $helper             = $this->getHelper('question');
        $enteredEnvironment = $input->getArgument('environment');
        if (empty($enteredEnvironment)) {
            $question           = new Question('Please enter a environment to show: ', 'dev');
            $enteredEnvironment = $helper->ask($input, $output, $question);
        }

        return $enteredEnvironment;
    }

    private function print(array $summaryLog, OutputInterface $output): void
    {
        foreach ($summaryLog as $key => $value) {
            $output->writeln($key . ": " . $value);
        }
    }
}
