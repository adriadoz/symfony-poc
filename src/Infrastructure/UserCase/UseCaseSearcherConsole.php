<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\UserCase;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UseCaseSearcherConsole extends Command
{
    private $useCase;

    public function __construct(array $useCase)
    {
        parent::__construct();
        $this->useCase = $useCase;
    }

    protected function configure(): void
    {
        $this
            ->setName('service:summary')
            ->setDescription('Read and print active services');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        foreach ($this->useCase as $item) {
            $output->writeln($item);
        }
    }
}
