<?php

declare(strict_types=1);

namespace App\AirTraffic;

use App\AirTraffic\Domain\Airport;
use App\AirTraffic\Domain\AirtrafficPosition;
use App\AirTraffic\Domain\DataSource\AirportRepository;
use App\AirTraffic\Domain\DataSource\AirtrafficPositionRepository;

final class AirportUpdater
{
    public function __construct(
        private AirtrafficPositionRepository $positionRepository,
        private AirportRepository            $airportRepository
    ) {}

    public function __invoke(...$airports): void
    {
        /** @var Airport $airport */
        foreach ($airports as $airport) {
            $this->positionRepository->put(AirtrafficPosition::forAirport($airport));
        }

        $this->airportRepository->put(...$airports);
    }
}