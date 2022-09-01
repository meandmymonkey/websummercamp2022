<?php

namespace App\AirTraffic\Domain\DataSource;

use App\AirTraffic\Domain\Airport;

interface AirportRepository
{
    public function put(...$airports): void;

    public function get(string $icaoCode): ?Airport;

    public function find(string $phrase): iterable;

    public function clear(): void;
}