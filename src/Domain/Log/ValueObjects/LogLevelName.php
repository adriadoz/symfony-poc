<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log\ValueObjects;

final class LogLevelName
{
    static function Error(): string
    {
        $level = new LogErrorLevel();

        return $level->value();
    }

    static function Critical(): string
    {
        $level = new LogCriticalLevel();

        return $level->value();
    }

    static function Warning(): string
    {
        $level = new LogWarningLevel();

        return $level->value();
    }

    static function Debug(): string
    {
        $level = new LogDebugLevel();

        return $level->value();
    }

    static function Info(): string
    {
        $level = new LogInfoLevel();

        return $level->value();
    }
}
