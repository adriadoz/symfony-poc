<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Endpoint;

use G3\FrameworkPractice\Application\Endpoint\LogApiBuilder;
use G3\FrameworkPractice\Domain\Log\LogEntry;
use G3\FrameworkPractice\Domain\Log\Repository\LogRepositoryInterface;
use G3\FrameworkPractice\Infrastructure\Log\LogSummaryGetter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Twig_Environment;
use Twig_Loader_Filesystem;

final class LogApiController extends Controller
{
    private const PATH = '../var/log/';
    private $repository;
    private $environment;
    private $loader;
    private $twig;

    public function __construct(LogRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->loader = new Twig_Loader_Filesystem('../templates');
        $this->twig = new Twig_Environment($this->loader, array(
            'cache' => '../var/cache',
        ));
    }

    public function read(string $environment, Request $request): Response
    {
        $this->environment = $environment;
        $filters = $request->query->get('filter');

        $logSummary    = new LogSummaryGetter(self::PATH, $environment);
        $logApiBuilder = new LogApiBuilder($logSummary->__invoke());

        $response = $this->setContent($filters, $logApiBuilder);
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

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
        $response = new Response();
        if (!isset($filters)) {
            $response->setContent($logApiBuilder->logSummaryFilter($filters));
            $this->twig->render('logsummary.html.twig', array('environment' => $this->environment, 'logs'=>$logApiBuilder->logSummary()));
        }

        if (isset($filters)) {
            $response->setContent($logApiBuilder->logSummaryFilter($filters));
            $this->twig->render('logsummary.html.twig', array('environment' => $this->environment, 'logs'=>$logApiBuilder->logSummaryFilter($filters)));
        }

        return $response;
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
