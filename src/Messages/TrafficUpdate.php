<?php

declare(strict_types=1);

namespace App\Messages;

use App\AirTraffic\Domain\TrafficEvent;

class TrafficUpdate
{
    public function __construct(public readonly TrafficEvent $event) {}
}