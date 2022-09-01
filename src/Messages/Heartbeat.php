<?php

declare(strict_types=1);

namespace App\Messages;

class Heartbeat
{
    public function __construct(public readonly \DateTimeImmutable $createdAt, public readonly int $ttl) {}

    public function isValid(\DateTimeImmutable|null $comparedToTime = null): bool
    {
        if ($comparedToTime === null) {
            $comparedToTime = new \DateTimeImmutable('now');
        }

        return $this->createdAt->modify($this->ttl.' seconds') >= $comparedToTime;
    }
}