<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Application\MessageCommand;

final class SayHello
{
    public function __invoke()
    {
        return "Hello Word";
    }
}