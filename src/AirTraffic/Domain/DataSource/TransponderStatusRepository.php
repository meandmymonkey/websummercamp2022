<?php

namespace App\AirTraffic\Domain\DataSource;

use App\AirTraffic\Domain\TransponderStatus;

interface TransponderStatusRepository
{
    public function put(TransponderStatus ...$transponderStatus): void;

    public function get(string $icao24): ?TransponderStatus;
}