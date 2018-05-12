<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Domain\Log\ValueObjects;

final class LogLevelName
{
    static function Error() {
        $level = new LogErrorLevel();
        return $level->value();
    }

    static function Critical() {
        $level = new LogCriticalLevel();
        return $level->value();
    }

    static function Warning() {
        $level = new LogWarningLevel();
        return $level->value();
    }

    static function Debug() {
        $level = new LogDebugLevel();
        return $level->value();
    }

    static function Info() {
        $level = new LogInfoLevel();
        return $level->value();
    }
}
