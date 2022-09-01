<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain\TrafficEvent;

use App\AirTraffic\Domain\Aircraft;
use App\AirTraffic\Domain\Airport;
use App\AirTraffic\Domain\TrafficEvent;
use App\AirTraffic\Domain\TransponderStatus;

final class Takeoff implements TrafficEvent
{
    public function __construct(
        public readonly TransponderStatus $transponderStatus,
        public readonly Aircraft|null $aircraft,
        public readonly Airport|null $fromAirport
    ) {}

    public function getTransponderStatus(): TransponderStatus
    {
        return $this->transponderStatus;
    }

    public function getDisplayString(): string
    {
        return sprintf(
            '[ICAO (%s)] Takeoff - flight %s (%s) took off from %s.',
            $this->transponderStatus->icao24,
            $this->transponderStatus->callsign ?? '[unknown]',
            $this->aircraft?->operatorCallsign ?? 'anonymous',
            $this->fromAirport?->iataCode ?? '[unknown]'
        );
    }
}