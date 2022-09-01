<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain\TrafficEvent;

use App\AirTraffic\Domain\Aircraft;
use App\AirTraffic\Domain\Airport;
use App\AirTraffic\Domain\TrafficEvent;
use App\AirTraffic\Domain\TransponderStatus;

final class Touchdown implements TrafficEvent
{
    public function __construct(
        public readonly TransponderStatus $transponderStatus,
        public readonly Aircraft|null $aircraft,
        public readonly Airport|null $atAirport
    ) {}

    public function getTransponderStatus(): TransponderStatus
    {
        return $this->transponderStatus;
    }

    public function getDisplayString(): string
    {
        return sprintf(
            '[ICAO (%s)] Touchdown - flight %s (%s) has landed at %s.',
            $this->transponderStatus->icao24,
            $this->transponderStatus->callsign ?? '[unknown]',
            $this->aircraft?->operatorCallsign ?? 'anonymous',
            $this->atAirport?->iataCode ?? '[unknown]'
        );
    }
}