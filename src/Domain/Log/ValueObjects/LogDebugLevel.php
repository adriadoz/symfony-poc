<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log\ValueObjects;

final class LogDebugLevel
{
    private const LEVEL = 'DEBUG';

    public function value(): string
    {
        return self::LEVEL;
    }
}
