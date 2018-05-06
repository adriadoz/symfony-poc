<?php
declare(strict_types = 1);

namespace G3\FrameworkPractice\Types\ValueObject;

final class Level
{
    const LEVELS = ["INFO", "WARNING", "ERROR", "DEBUG", "CRITICAL"];

    private $level;

    private function __construct(string $level)
    {
        $this->level = $level;
    }

    public static function fromString(string $level)
    {
        if(in_array($level, Level::LEVELS)){
            return new self($level);
        }
        throw new \Error("Not a valid level");
    }

    public function __toString()
    {
        return (string)$this->level;
    }

    public function get(): string
    {
        return $this->level;
    }
}