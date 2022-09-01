<?php

declare(strict_types=1);

namespace App\Messages;

use App\AirTraffic\Domain\TransponderStatus;

final class IncomingTransponderStatus
{
    public function __construct(public readonly TransponderStatus $transponderStatus) {}
}