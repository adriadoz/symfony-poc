<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Endpoint;

use G3\FrameworkPractice\Application\Endpoint\LogApiBuilder;
use G3\FrameworkPractice\Infrastructure\Log\LogSummaryGetter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


final class LogApiController
{
    const PATH = '../var/log/';

    /**
     * @Route("/logs", name="log_api")
     * @Method({"GET"})
     */
    public function __invoke(): Response
    {
        $logSummary    = new LogSummaryGetter(self::PATH, $_SERVER['APP_ENV']);
        $logApiBuilder = new LogApiBuilder($logSummary->__invoke());

        $jsonRequest = $logApiBuilder->__invoke();

        $response = new Response();
        $response->setContent($jsonRequest);
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

        return $response;
    }
}
