<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log\ValueObjects;

final class LogInfoLevel
{
    private const LEVEL = 'INFO';

    public function value(): string
    {
        return self::LEVEL;
    }
}
