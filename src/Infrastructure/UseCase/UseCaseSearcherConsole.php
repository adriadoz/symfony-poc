<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\UseCase;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UseCaseSearcherConsole extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('service:summary')
            ->setDescription('Read and print active services');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $kernel   = $this->getApplication()->getKernel();
        $usesCase = new UseCaseSearcher();
        $usesCase->__invoke($kernel);
    }
}
