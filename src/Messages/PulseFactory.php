<?php

declare(strict_types=1);

namespace App\Messages;

use StellaMaris\Clock\ClockInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;

final class PulseFactory
{
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly int $interval = 10
    ) {}

    public function beats(): \Generator
    {
        $ttl = (int) floor($this->interval * .8);

        while (true) {
            yield new Envelope(
                new Heartbeat($this->clock->now(), $ttl),
                [new AmqpStamp(attributes: ['expiration' => $ttl * 1000])]
            );

            sleep($this->interval);
        }
    }
}