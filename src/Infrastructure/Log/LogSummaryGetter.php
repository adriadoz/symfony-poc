<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use G3\FrameworkPractice\Application\Log\LogSummaryBuilder;
use G3\FrameworkPractice\Domain\Log\LogEntry;
use G3\FrameworkPractice\Domain\Log\LogEntryCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class LogSummaryGetter
{
    private $environment;
    private $path;

    public function __construct(string $path, string $environment)
    {
        $this->environment = $environment;
        $this->path        = $path;
    }

    public function __invoke(): LogSummaryBuilder
    {
        $finder = new Finder();
        $finder->files()->in($this->path . $this->environment);

        $logCollection = $this->getLastFiles($finder);
        $logSummary    = new LogSummaryBuilder($logCollection);

        return $logSummary;
    }

    private function getLastFiles(Finder $finder): LogEntryCollection
    {
        $logCollection = new LogEntryCollection();
        foreach ($finder as $file) {
            if (!empty($file->getContents())) {
                $contentFile = $this->toArray($file->getContents());
                $this->serializer($contentFile, $logCollection);
            }
        }

        return $logCollection;
    }

    private function serializer(array $file, LogEntryCollection $logCollection): void
    {
        $metadataFactory                   = null;
        $camelCaseToSnakeCaseNameConverter = new CamelCaseToSnakeCaseNameConverter();

        $normalizers = [new ObjectNormalizer($metadataFactory, $camelCaseToSnakeCaseNameConverter)];
        $encoders    = [new JsonEncoder()];
        $serializer  = new Serializer($normalizers, $encoders);

        foreach ($file as $line) {
            $logCollection->add($this->deserialize($line, $serializer));
        }
    }

    private function deserialize($lineContent, Serializer $serializer): LogEntry
    {
        $logEntry = $serializer->deserialize(
            $lineContent,
            LogEntry::class,
            'json',
            ['allow_extra_attributes' => false]
        );

        return $logEntry;
    }

    private function toArray(string $content): array
    {
        $text  = trim($content, "\n");
        $array = explode("\n", $text);

        return $array;
    }
}
