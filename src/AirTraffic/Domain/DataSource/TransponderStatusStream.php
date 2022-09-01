<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain\DataSource;

interface TransponderStatusStream
{
    public function latestData(): iterable;
}