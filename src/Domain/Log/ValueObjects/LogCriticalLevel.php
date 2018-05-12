<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log\ValueObjects;

final class LogCriticalLevel
{
    private const LEVEL = 'CRITICAL';

    public function value(): string
    {
        return self::LEVEL;
    }
}
