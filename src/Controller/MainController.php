<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

final class MainController extends Controller
{
    private $environment;
    private $environmentName;

    public function __construct(string $environment, string $environmentName)
    {
        $this->environment     = $environment;
        $this->environmentName = $environmentName;
    }

    public function showHelloEnv()
    {
        return new Response(
            '<html><body>Hello ' . $this->environment . ' World</body></html>'
        );
    }

    public function showHelloEnvName()
    {
        return new Response(
            '<html><body>Hello ' . $this->environmentName . ' World</body></html>'
        );
    }
}
