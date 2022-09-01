<?php

namespace App\AirTraffic\Domain\DataSource;

use App\AirTraffic\Domain\AirtrafficPosition;
use App\AirTraffic\Domain\Position;
use App\AirTraffic\Domain\PositionType;

interface AirtrafficPositionRepository
{
    public function put(AirtrafficPosition $position): void;

    public function findClosestTo(Position $position, PositionType $positionType, int $limitKm = 5): ?AirtrafficPosition;

    public function findInRadius(Position $position, PositionType $positionType, int $radius): iterable;
}