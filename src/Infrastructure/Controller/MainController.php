<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Controller;

use G3\FrameworkPractice\Infrastructure\Log\LogEventDispatcher;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Prooph\ServiceBus\CommandBus;


final class MainController extends Controller
{
    private $environment;
    private $environmentName;
    private $logger;
    private $eventDispatcher;
    private $router;


    public function __construct(
        string $environment,
        string $environmentName,
        LoggerInterface $logger
    ) {
        $this->logger          = $logger;
        $this->environment     = $environment;
        $this->environmentName = $environmentName;
        $this->eventDispatcher = new CommandBus();
        $this->router = new CommandRouter();
        $this->router->route('log_record.locally_raised')->to(new LogEventDispatcher($this->environment));
        $this->router->attachToMessageBus($this->eventDispatcher);
    }

    public function showHelloEnv(Request $request): Response
    {
        $this->setLogRecords($request);

        return new Response(
            '<html><body>Hello ' . $this->environment . ' World</body></html>'
        );
    }

    public function showHelloEnvName(Request $request): Response
    {
        $this->setLogRecords($request);

        return new Response(
            '<html><body>Hello ' . $this->environmentName . ' World</body></html>'
        );
    }

    private function setLogRecords(Request $request): void
    {
        $this->logger->info('Info to the log on each request to Hello Word', ["Info" => "Context Param"]);
        $this->logger->warning('Warning to the log on each request to Hello Word', ["Warning" => "Context Param"]);

        if ($request->query->has('bum')) {
            $this->logger->error('Error, send GET key bum', ["Error" => "Context Param"]);

            $this->eventDispatcher->dispatch('log_record.locally_raised');
        }
    }
}
