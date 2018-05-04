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
        return $this->logToJson();
    }

    private function logToJson(): string
    {
        $logs = [];
        foreach ($this->content->items() as $item) {
            $log['message']   = $item->message();
            $log['channel']   = $item->channel();
            $log['levelName'] = $item->levelName();
            array_push($logs, $log);
        }

        return json_encode($logs);
    }
}
