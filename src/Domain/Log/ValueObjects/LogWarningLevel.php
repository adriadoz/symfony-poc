<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log\ValueObjects;

final class LogWarningLevel
{
    private const LEVEL = 'WARNING';

    public function value(): string
    {
        return self::LEVEL;
    }
}
