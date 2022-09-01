<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\AirTraffic\TransponderStatusUpdater;
use App\Messages\IncomingTransponderStatus;
use App\Messages\TrafficUpdate;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;use Symfony\Component\Messenger\Envelope;use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class CheckForTrafficEvents
{
    public function __construct(
        private readonly TransponderStatusUpdater $statusUpdater,
        private readonly MessageBusInterface $messageBus
    ) {}

    public function __invoke(IncomingTransponderStatus $incomingTransponderStatus): void
    {
        $trafficEvents = ($this->statusUpdater)($incomingTransponderStatus->transponderStatus);

        foreach ($trafficEvents as $trafficEvent) {
            $this->messageBus->dispatch(
                new Envelope(
                    new TrafficUpdate($trafficEvent),
                    [new AmqpStamp(attributes: ['expiration' => 600000])]
                )
            );
        }
    }
}