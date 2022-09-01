<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain\TransponderStatusChecker;

use App\AirTraffic\Domain\Aircraft;
use App\AirTraffic\Domain\Airport;
use App\AirTraffic\Domain\DataSource\AircraftRepository;
use App\AirTraffic\Domain\DataSource\AirportRepository;
use App\AirTraffic\Domain\DataSource\AirtrafficPositionRepository;
use App\AirTraffic\Domain\PositionType;
use App\AirTraffic\Domain\TransponderStatus;
use App\AirTraffic\Domain\TransponderStatusChecker;

abstract class AbstractChecker implements TransponderStatusChecker
{
    public function __construct(
        private readonly AircraftRepository $aircraftRepository,
        private readonly AirportRepository $airportRepository,
        private readonly AirtrafficPositionRepository $positionRepository
    ) {}

    protected function getAircraftForTransponder(TransponderStatus $transponderStatus): ?Aircraft
    {
        return $this->aircraftRepository->get($transponderStatus->icao24);
    }

    protected function getAirportAtAircraft(TransponderStatus $transponderStatus): ?Airport
    {
        $closestPosition = $this->positionRepository->findClosestTo($transponderStatus->location, PositionType::Airport);

        return $this->airportRepository->get($closestPosition->icaoCode);
    }
}