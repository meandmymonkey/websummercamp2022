<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Repository;

use App\AirTraffic\Domain\AirtrafficPosition;
use App\AirTraffic\Domain\DataSource\AirtrafficPositionRepository;
use App\AirTraffic\Domain\Position;
use App\AirTraffic\Domain\PositionType;
use Predis\ClientInterface;

class RedisAirtrafficPositionRepository implements AirtrafficPositionRepository
{
    private const SET_NAME = 'positions';

    public function __construct(private readonly ClientInterface $redis) {}

    public function put(AirtrafficPosition $position): void
    {
        $this->redis->geoadd(
            self::SET_NAME.'://'.$position->type->value,
            $position->location->longitude,
            $position->location->latitude,
            $position->icaoCode
        );
    }

    public function findClosestTo(Position $position, PositionType $positionType, int $limitKm = 10): ?AirtrafficPosition
    {
        $data = $this->findInRadius($position, $positionType, $limitKm);

        if (empty($data)) {
            return null;
        }

        return new AirtrafficPosition((string) $data[0], $positionType, $position);
    }

    public function findInRadius(Position $position, PositionType $positionType, int $radius): iterable
    {
        return $this->redis->georadius(
            self::SET_NAME.'://'.$positionType->value,
            $position->longitude,
            $position->latitude,
            $radius,
            'km'
        );
    }
}