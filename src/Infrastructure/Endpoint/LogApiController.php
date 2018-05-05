<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Endpoint;

use G3\FrameworkPractice\Application\Endpoint\LogApiBuilder;
use G3\FrameworkPractice\Infrastructure\Log\LogSummaryGetter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LogApiController
{
    private const PATH = '../var/log/';

    public function read(string $environment, Request $request): Response
    {
        $filters = $request->query->get('filter');

        $logSummary    = new LogSummaryGetter(self::PATH, $environment);
        $logApiBuilder = new LogApiBuilder($logSummary->__invoke());

        $response = $this->setContent($filters, $logApiBuilder);
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

        return $response;
    }

    public function add(): void
    {
        //TODO: Future implementation for add log external
    }

    public function LogsOnMethodNotImplement(): Response
    {
        return $this->whenTheMethodHasNotBeenImplemented();
    }

    private function setContent($filters, LogApiBuilder $logApiBuilder): Response
    {
        $response = new Response();
        if (!isset($filters)) {
            $response->setContent($logApiBuilder->logSummary());
        }

        if (isset($filters)) {
            $response->setContent($logApiBuilder->logSummaryFilter($filters));
        }

        return $response;
    }

    private function whenTheMethodHasNotBeenImplemented(): Response
    {
        $response = new Response();
        $response->setStatusCode(405);

        return $response;
    }
}
