<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain\TrafficEvent;

use App\AirTraffic\Domain\Aircraft;
use App\AirTraffic\Domain\Airport;
use App\AirTraffic\Domain\TrafficEvent;
use App\AirTraffic\Domain\TransponderStatus;

final class NewTransponder implements TrafficEvent
{
    public function __construct(
        public readonly TransponderStatus $status,
        public readonly Aircraft|null $aircraft
    ) {}

    public function getTransponderStatus(): TransponderStatus
    {
        return $this->status;
    }

    public function getDisplayString(): string
    {
        return sprintf(
            '[ICAO (%s)] New transponder found with callsign %s, located at %s, %s at %s m AMSL, vehicle is %s.',
            $this->status->icao24,
            $this->status->callsign,
            $this->status->location->latitude,
            $this->status->location->longitude,
            $this->status->location->elevation,
            $this->status->airborne ? 'airborne' : 'on the ground'
        );
    }
}