<?php

declare(strict_types=1);

namespace App\AirTraffic\DataImport\Mapper;

use App\AirTraffic\Domain\Position;
use IteratorAggregate;

final class SanitizedTransponderStatusSource implements IteratorAggregate
{
    private iterable $data;

    public function __construct(array $rawData)
    {
        $this->data = $this->sanitize($rawData);
    }

    private function sanitize(array $data): iterable
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = empty($value) ? null : trim($value);
            }
        }

        $receivedAt = array_shift($data);

        $result = [
            'icao24' => $data[0],
            'callsign' => $data[1],
            'originCountry' => $data[2],
            'lastUpdate' => \DateTimeImmutable::createFromFormat('U', (string)(($data[4] ?? $data[3]) ?? time())),
            'location' => new Position(
                $data[6],
                $data[5],
                (int)($data[13] ?? $data[7])
            ),
            'airborne' => !(bool)$data[8],
            'velocity' => $data[9],
            'track' => $data[10],
            'verticalRate' => $data[11],
            'receivedAt' => \DateTimeImmutable::createFromFormat('U', (string) $receivedAt)
        ];

        return $result;
    }

    public function getIterator()
    {
        yield from $this->data;
    }
}