<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Endpoint;

use G3\FrameworkPractice\Application\Endpoint\LogApiBuilder;
use G3\FrameworkPractice\Domain\Log\LogEntry;
use G3\FrameworkPractice\Domain\Log\Repository\LogRepositoryInterface;
use G3\FrameworkPractice\Infrastructure\Log\LogSummaryGetter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LogApiController extends Controller
{
    private const PATH = '../var/log/';
    private $repository;
    private $environment;

    public function __construct(LogRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function read(string $environment, Request $request): Response
    {
        $this->environment = $environment;
        $filters           = $request->query->get('filter');

        $logSummary    = new LogSummaryGetter(self::PATH, $environment);
        $logApiBuilder = new LogApiBuilder($logSummary->__invoke());

        $response = $this->setContent($filters, $logApiBuilder);
        $response->headers->set('Content-Type', 'text/html; charset=UTF-8');

        return $response;
    }

    public function add(Request $request): Response
    {
        $type    = strtoupper($request->query->get('type'));
        $message = $request->query->get('message');

        $logEntry = new LogEntry($message, "app", $type);

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

    private function setContent($filters, LogApiBuilder $logApiBuilder): Response
    {
        if (!isset($filters)) {
            return $this->render(
                'logSummary.html.twig',
                [
                    'environment' => $this->environment,
                    'logs'        => json_decode($logApiBuilder->logSummary(), true),
                ]
            );
        }

        return $this->render(
            'logSummary.html.twig',
            [
                'environment' => $this->environment,
                'logs'        => json_decode($logApiBuilder->logSummaryFilter($filters), true),
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
}
