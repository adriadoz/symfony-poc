<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use Symfony\Component\Finder\Finder;

final class LogSummary
{
    private $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function __invoke(): string
    {
        $finder = new Finder();
        $finder->files()->in('var/log/' . $this->environment);

        $contents = $this->getLastFiles($finder);
        return json_encode($contents);
    }

    private function getLastFiles(Finder $finder): array
    {
        $contents = [];

        foreach ($finder as $file) {
            if (!empty($file->getContents())) {
                array_push($contents, $file->getContents());
            }
        }

        return $contents;
    }
}
