<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use G3\FrameworkPractice\Application\Log\LogSummaryCalculator;
use G3\FrameworkPractice\Infrastructure\Repository\JsonLogSummaryRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class LogSummaryConsole extends Command
{
    const PATH = 'var/log/';

    protected function configure(): void
    {
        $this
            ->setName('log:summary')
            ->setDescription('Print summary of last 15 day log')
            ->addArgument(
                'environment',
                InputArgument::OPTIONAL,
                '[string] Enter a environment to show. Example: "dev"'
            )
            ->addArgument('levels', InputArgument::OPTIONAL, '[string] Enter levels to show. Example: "warning,error"');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $environment = $this->getEnvironment($input, $output);
        $levelsRaw   = $this->getLevels($input, $output);
        $levels      = $this->toArray($levelsRaw);
        $logSummaryRepo = new JsonLogSummaryRepository(SELF::PATH);
        $logSummaryCalculator = new LogSummaryCalculator($environment, SELF::PATH);
        $logGetters  = new LogSummaryGetter($environment, $logSummaryRepo, $logSummaryCalculator);
        $logSummary  = $logGetters->__invoke();
        $this->print($logSummary->filterByLevels($levels), $output);
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

    private function getLevels(InputInterface $input, OutputInterface $output): string
    {
        $helper        = $this->getHelper('question');
        $enteredLevels = $input->getArgument('levels');
        if (empty($enteredLevels)) {
            $question      = new Question('Please enter levels to show separated by comma: ', 'dev,info,warning,error');
            $enteredLevels = $helper->ask($input, $output, $question);
        }
        $enteredLevelNotSpaces = str_replace(' ', '', $enteredLevels);

        return $enteredLevelNotSpaces;
    }

    private function print(array $summaryLog, OutputInterface $output): void
    {
        foreach ($summaryLog as $key => $value) {
            $output->writeln($key . ": " . $value);
        }
    }

    private function toArray($levels): array
    {
        $lowCaseLevels = strtoupper($levels);
        $levels        = explode(",", $lowCaseLevels);

        return $levels;
    }
}
