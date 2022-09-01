<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

final class AirtrafficPosition
{
    public function __construct(
        public readonly string $icaoCode,
        public readonly PositionType $type,
        public readonly Position $location
    ) {}

    public static function forAirport(Airport $airport): self
    {
        return new self($airport->icaoCode, PositionType::Airport, $airport->location);
    }
}