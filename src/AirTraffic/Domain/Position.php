<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

class Position
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly int|null $elevation = null,
    ) {
        if ($latitude < -85.05112878 || $latitude > 85.05112878) {
            throw DataIntegrityException::forInvalidLatitude($latitude);
        }
        if ($longitude < -180 || $longitude > 180) {
            throw DataIntegrityException::forInvalidLongitude($longitude);
        }
        if ($elevation !== null && $elevation < -408) {
            throw DataIntegrityException::forInvalidElevation($elevation);
        }
    }
}