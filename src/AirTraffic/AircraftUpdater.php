<?php

declare(strict_types=1);

namespace App\AirTraffic;

use App\AirTraffic\Domain\DataSource\AircraftRepository;

final class AircraftUpdater
{
    public function __construct(
        private AircraftRepository $aircraftRepository
    ) {}

    public function __invoke(...$aircraft): void
    {
        $this->aircraftRepository->put(...$aircraft);
    }
}