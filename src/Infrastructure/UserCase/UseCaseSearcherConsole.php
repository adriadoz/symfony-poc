<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\UserCase;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class UseCaseSearcherConsole extends Command
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure(): void
    {
        $this
            ->setName('service:summary')
            ->setDescription('Read and print active services');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $usesCase = new UseCaseSearcher();
        $usesCase->__invoke($this->container);
    }
}
