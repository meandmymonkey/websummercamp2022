<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Messages\Ping;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PingHandler
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public function __invoke(Ping $ping): void
    {
        $this->logger->alert($ping->__toString());
    }
}