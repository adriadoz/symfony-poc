<?php

declare(strict_types = 1);

namespace G3\FrameworkPractice\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="log_summary")
 */
final class LogSummaryEntry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $level;
    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $total;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $environment;

    public function __construct(string $level, int $total, string $environment)
    {
        $this->level       = $level;
        $this->total       = $total;
        $this->environment = $environment;
    }

    public function level(): string
    {
        return $this->level;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function environment(): string
    {
        return $this->environment;
    }
}
