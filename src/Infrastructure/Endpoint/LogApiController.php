<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Endpoint;

use G3\FrameworkPractice\Application\Log\LogSummaryCalculator;
use G3\FrameworkPractice\Domain\Log\LogEntry;
use G3\FrameworkPractice\Domain\Log\LogSummary;
use G3\FrameworkPractice\Domain\Log\Repository\LogRepositoryInterface;
use G3\FrameworkPractice\Domain\Log\ValueObjects\LogLevelName;
use G3\FrameworkPractice\Infrastructure\Log\LogEventDispatcher;
use G3\FrameworkPractice\Infrastructure\Log\LogSummaryGetter;
use G3\FrameworkPractice\Infrastructure\Repository\MySQLogSummaryRepository;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LogApiController extends Controller
{
    private const CHANNEL = "external";
    private const PATH    = "../var/log/";
    private $repository;
    private $environment;
    private $eventDispatcher;
    private $router;

    public function __construct(LogRepositoryInterface $repository)
    {
        $this->repository      = $repository;
        $this->environment     = 'dev';
        $this->eventDispatcher = new CommandBus();
        $this->router          = new CommandRouter();
        $this->router->route('log_record.remotely_added')->to(new LogEventDispatcher($this->environment));
        $this->router->attachToMessageBus($this->eventDispatcher);
    }

    public function read(string $environment, Request $request): Response
    {
        $this->environment = $environment;

        $levels               = $this->getFilteredLevel($request);
        $logSummaryRepo       = new MySQLogSummaryRepository();
        $logSummaryCalculator = new LogSummaryCalculator($this->environment, self::PATH);
        $logSummary           = new LogSummaryGetter($environment, $logSummaryRepo, $logSummaryCalculator);

        $response = $this->setContent($levels, $logSummary->__invoke());
        $response->headers->set('Content-Type', 'text/html; charset=UTF-8');

        return $response;
    }

    public function add(Request $request): Response
    {
        $type    = strtoupper($request->query->get('type'));
        $message = $request->query->get('message');

        $this->onErrorDispatcher($type);

        $logEntry = new LogEntry($message, self::CHANNEL, $type);

        $logger = $this->getLogExternalChannel();

        $this->repository->saveLog($logger, $logEntry);

        $response = new Response();
        $response->setStatusCode(201);

        return $response;
    }

    public function LogsOnMethodNotImplement(): Response
    {
        return $this->whenTheMethodHasNotBeenImplemented();
    }

    private function setContent(array $levels, LogSummary $logSummary): Response
    {
        return $this->render(
            'logSummary.html.twig',
            [
                'environment' => $this->environment,
                'logs'        => $logSummary->__invoke($levels),
            ]
        );
    }

    private function whenTheMethodHasNotBeenImplemented(): Response
    {
        $response = new Response();
        $response->setStatusCode(405);

        return $response;
    }

    private function getLogExternalChannel()
    {
        $logger = $this->get('monolog.logger.external');

        return $logger;
    }

    private function toArray($levels): array
    {
        $lowCaseLevels = strtoupper($levels);
        $levels        = explode(",", $lowCaseLevels);

        return $levels;
    }

    private function getFilteredLevel(Request $request): array
    {
        $filter = $request->query->get('filter');

        if (empty($filter)) {
            return [];
        }

        $levels = $this->toArray($filter['level']);

        return $levels;
    }

    private function onErrorDispatcher($type): void
    {
        $errorType = LogLevelName::Error();

        if ($type === $errorType) {
            $this->eventDispatcher->dispatch('log_record.remotely_added');
        }
    }
}
