<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Repository;

use App\AirTraffic\Domain\DataIntegrityException;
use App\AirTraffic\Domain\DataSource\TransponderStatusRepository;
use App\AirTraffic\Domain\Position;
use App\AirTraffic\Domain\TransponderStatus;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use Predis\ClientInterface;

final class RedisTransponderStatusRepository implements TransponderStatusRepository
{
    private const SET_NAME = 'transponder://';
    private const RECORD_TTL = 60;

    public function __construct(private readonly ClientInterface $redis) {}

    public function put(TransponderStatus ...$transponderStatus): void
    {
        foreach ($transponderStatus as $status) {
            $this->redis->set(
                self::SET_NAME.$status->icao24,
                serialize($status),
                'EX',
                self::RECORD_TTL
            );
        }
    }

    public function get(string $icao24): ?TransponderStatus
    {
        $data = $this->redis->get(self::SET_NAME.$icao24);

        if ($data === null) {
            return null;
        }

        return unserialize($data);
    }
}