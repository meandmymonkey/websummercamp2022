<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Messages\IncomingTransponderStatus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CheckForTrafficEvents
{
    public function __invoke(IncomingTransponderStatus $incomingTransponderStatus): void
    {
        dump($incomingTransponderStatus);
    }
}