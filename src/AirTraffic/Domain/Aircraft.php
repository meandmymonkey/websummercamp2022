<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

class Aircraft
{
    public readonly \DateTimeImmutable $updatedAt;

    public function __construct(
        public readonly string $icao24,
        public readonly string|null $registration,
        public readonly string|null $manufacturerIcao,
        public readonly string|null $manufacturerName,
        public readonly string|null $model,
        public readonly string|null $typeCode,
        public readonly string|null $serialNumber,
        public readonly string|null $lineNumber,
        public readonly string|null $icaoAircraftType,
        public readonly string|null $operator,
        public readonly string|null $operatorCallsign,
        public readonly string|null $operatorIcao,
        public readonly string|null $operatorIata,
        public readonly string|null $owner,
        public readonly \DateTimeImmutable|null $registered,
        public readonly \DateTimeImmutable|null $regUntil,
        public readonly string|null $status,
        public readonly \DateTimeImmutable|null $built,
        public readonly \DateTimeImmutable|null $firstFlightDate,
        public readonly string|null $seatConfiguration,
        public readonly string|null $engines,
        public readonly string|null $notes,
        public readonly string|null $categoryDescription
    ) {
        $this->updatedAt = new \DateTimeImmutable('now');
    }
}