<?php

namespace App\AirTraffic\Domain\DataSource;

use App\AirTraffic\Domain\Aircraft;

interface AircraftRepository
{
    public function put(...$aircraftList): void;

    public function get(string $icao24): ?Aircraft;

    public function find(string $phrase): iterable;

    public function clear(): void;
}