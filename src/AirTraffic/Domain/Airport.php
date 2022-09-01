<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

class Airport
{
    public readonly \DateTimeImmutable $updatedAt;

    public function __construct(
        public readonly string        $icaoCode,
        public readonly string|null   $type = null,
        public readonly string|null   $name = null,
        public readonly Position|null $location = null,
        public readonly string|null   $continent = null,
        public readonly string|null   $isoCountry = null,
        public readonly string|null   $isoRegion = null,
        public readonly string|null   $municipality = null,
        public readonly bool          $scheduledService = false,
        public readonly string|null   $gpsCode = null,
        public readonly string|null   $iataCode = null,
        public readonly string|null   $localCode = null,
        public readonly string|null   $webHomepage = null,
        public readonly string|null   $webWikipedia = null,
        public readonly string|null $keywords = null
    ) {
        $this->updatedAt = new \DateTimeImmutable('now');
    }
}