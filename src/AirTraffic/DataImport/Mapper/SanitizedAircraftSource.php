<?php

declare(strict_types=1);

namespace App\AirTraffic\DataImport\Mapper;

use IteratorAggregate;

final class SanitizedAircraftSource implements IteratorAggregate
{
    private iterable $data;

    public function __construct(iterable $rawData)
    {
        $this->data = $this->sanitize($rawData);
    }

    private function sanitize(iterable $data): iterable
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = empty($value) ? null : trim($value);
            }
        }

        foreach (['registered', 'regUntil', 'built', 'firstFlightDate'] as $key) {
            $data[$key] = empty($data[$key]) ? null : \DateTimeImmutable::createFromFormat('Y-m-d', $data[$key]);
        }

        return $data;
    }

    public function getIterator()
    {
        yield from $this->data;
    }
}