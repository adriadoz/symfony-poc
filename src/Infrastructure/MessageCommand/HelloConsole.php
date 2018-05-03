<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\MessageCommand;

use G3\FrameworkPractice\Application\MessageCommand\SayHello;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class HelloConsole extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('message:hello')
            ->setDescription('say Hello World')
            ->setHelp('type "php bin/console message:hello" to get "Hello World"');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $sayMessage = new SayHello();
        $output->writeln($sayMessage->__invoke());
    }
}
