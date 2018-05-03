<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

final class LogSummary extends Command
{
    private $environment;
    private const TYPE_LOG = 'json';

    public function __construct(string $environment)
    {
        parent::__construct();
        $this->environment = $environment;
    }

    protected function configure(): void
    {
        $this
            ->setName('log:summary')
            ->setDescription('Read and print last 15 day log file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $logsToShow = $this->getFile();
        $this->print($output, $logsToShow);
    }

    private function getFile(): string
    {
        $finder = new Finder();
        $finder->files()->in('var/log/' . $this->environment);

        $contents = [];

        foreach ($finder as $file) {
            if (!empty($file->getContents())) {
                array_push($contents, $file->getContents());
            }
        }

        return json_encode($contents);
    }

    private function print(OutputInterface $output, string $textToPrint): void
    {
        $output->writeln([$textToPrint]);
    }
}
