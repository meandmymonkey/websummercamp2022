<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

class TransponderStatus
{
    public function __construct(
        public readonly string             $icao24,
        public readonly string|null        $callsign,
        public readonly string|null        $originCountry,
        public readonly \DateTimeImmutable $lastUpdate,
        public readonly Position           $location,
        public readonly bool               $airborne,
        public readonly float|null         $velocity,
        public readonly float|null         $track,
        public readonly float|null         $verticalRate,
        public readonly \DateTimeImmutable $receivedAt
    ) {}
}