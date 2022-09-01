<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\AirTraffic\DataImport\TransponderStatusReader;
use App\Messages\Heartbeat;
use App\Messages\IncomingTransponderStatus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class UpdateTransponderStatusOnHeartbeat
{
    public function __construct(
        private readonly TransponderStatusReader $transponderUpdateReader,
        private readonly MessageBusInterface     $messageBus
    ) {}

    public function __invoke(Heartbeat $heartbeat): void
    {
        if (!$heartbeat->isValid()) {
            return;
        }

        foreach ($this->transponderUpdateReader->transponderStatus() as $transponderStatus) {
            $this->messageBus->dispatch(new IncomingTransponderStatus($transponderStatus));
        }
    }
}