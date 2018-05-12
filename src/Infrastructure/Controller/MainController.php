<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Controller;

use G3\FrameworkPractice\Infrastructure\Log\LogEventDispatcher;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MainController extends Controller
{
    private $environment;
    private $environmentName;
    private $logger;

    public function __construct(string $environment, string $environmentName, LoggerInterface $logger)
    {
        $this->environment     = $environment;
        $this->environmentName = $environmentName;
        $this->logger          = $logger;
    }

    public function showHelloEnv(Request $request)
    {
        $this->setLogRecords($request);

        return new Response(
            '<html><body>Hello ' . $this->environment . ' World</body></html>'
        );
    }

    public function showHelloEnvName(Request $request)
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
            $this->addDispatcher();
            $this->logger->error('Error, send GET key bum', ["Error" => "Context Param"]);
        }
    }

    private function addDispatcher(): void
    {
        $dispatcher      = new EventDispatcher();
        $logRecordRaised = 'log_record.locally_raised';

        $dispatcher->addListener(
            $logRecordRaised,
            function (LogEventDispatcher $event) {
                $event->locallyRaised();
            }
        );

        $dispatcher->dispatch($logRecordRaised, new LogEventDispatcher($logRecordRaised));
    }
}
