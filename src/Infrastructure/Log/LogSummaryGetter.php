<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Log;

use G3\FrameworkPractice\Application\Log\LogSummary;
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

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function __invoke(): LogSummary
    {
        $finder = new Finder();
        $finder->files()->in('var/log/' . $this->environment);

        $logCollection = $this->getLastFiles($finder);
        $logSummary    = new LogSummary($logCollection);

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