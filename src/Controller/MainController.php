<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    public function showHelloEnv()
    {
        $this->logger->info('Info to the log on each request to Hello Word');
        $this->logger->warning('Waring to the log on each request to Hello Word');

        return new Response(
            '<html><body>Hello ' . $this->environment . ' World</body></html>'
        );
    }

    public function showHelloEnvName()
    {

        $this->logger->info('Info to the log on each request to Hello Word');
        $this->logger->warning('Waring to the log on each request to Hello Word');

        return new Response(
            '<html><body>Hello ' . $this->environmentName . ' World</body></html>'
        );
    }
}
