<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log\ValueObjects;

final class LogErrorLevel
{
    private const LEVEL = 'ERROR';

    public function value(): string
    {
        return self::LEVEL;
    }
}
