<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Controller;

use G3\FrameworkPractice\Infrastructure\Log\LogEventDispatcher;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MainController extends Controller
{
    private $environment;
    private $environmentName;
    private $logger;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;


    public function __construct(
        string $environment,
        string $environmentName,
        LoggerInterface $logger,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->logger          = $logger;
        $this->environment     = $environment;
        $this->environmentName = $environmentName;
        $this->eventDispatcher = $eventDispatcher;
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

            $this->eventDispatcher->dispatch(
                'log_record.locally_raised',
                new LogEventDispatcher($this->environment)
            );
        }
    }
}
