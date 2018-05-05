<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Controller;

use G3\FrameworkPractice\Application\Log\LogSummary;
use G3\FrameworkPractice\Infrastructure\Log\LogSummaryGetter;
use Symfony\Component\HttpFoundation\Response;

final class LogApiController
{
    const PATH = '../var/log/';

    public function __invoke(): Response
    {
        $logSummary = new LogSummaryGetter(self::PATH, $_SERVER['APP_ENV']);
        $logs       = $logSummary->__invoke();
        $response   = new Response();
        $response->setContent($this->buildJson($logs));
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

        return $response;
    }

    private function buildJson(LogSummary $logs): string
    {
        return json_encode($logs->summaryLog());
    }
}
