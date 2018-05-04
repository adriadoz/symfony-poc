<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Application\Log;

use G3\FrameworkPractice\Domain\Log\LogEntryCollection;

final class LogSummary
{
    private $content;

    public function __construct(LogEntryCollection $content)
    {
        $this->content = $content;
    }

    public function __invoke(): string
    {
        dump($this->content);

        return '';
    }
}
