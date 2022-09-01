<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Messages\TrafficUpdate;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LogTrafficUpdatesHandler
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public function __invoke(TrafficUpdate $trafficUpdate): void
    {
        $this->logger->alert($trafficUpdate->event->getDisplayString());
    }
}