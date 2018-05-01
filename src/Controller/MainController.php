<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

final class MainController extends Controller
{
    public function index()
    {
        return new Response(
            '<html><body>Hello World</body></html>'
        );
    }
}
