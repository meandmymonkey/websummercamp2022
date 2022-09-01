<?php

declare(strict_types=1);

namespace App\AirTraffic\DataImport\Mapper;

use IteratorAggregate;

final class SanitizedAirportSource implements IteratorAggregate
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

        $data['icao_code'] = $data['ident'];
        unset($data['ident']);

        $data['location'] = [
            'latitude' => $data['latitude_deg'],
            'longitude' => $data['longitude_deg'],
            'elevation' => (int) round(0.3048 * (float) $data['elevation_ft'])
        ];
        unset($data['latitude_deg']);
        unset($data['longitude_deg']);
        unset($data['elevation_ft']);

        $data['scheduled_service'] = $data['scheduled_service'] === 'yes';

        return $data;
    }

    public function getIterator()
    {
        yield from $this->data;
    }
}