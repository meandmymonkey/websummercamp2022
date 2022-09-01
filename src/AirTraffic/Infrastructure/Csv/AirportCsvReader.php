<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Csv;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AirportCsvReader
{
    public function __construct(#[Autowire('%kernel.project_dir%/data/airports.csv')] private string $csvPath) {}

    public function entries(): \Generator
    {
        $csvReader = new CsvReader(
            [
                'id',
                'ident',
                'type',
                'name',
                'latitude_deg',
                'longitude_deg',
                'elevation_ft',
                'continent',
                'iso_country',
                'iso_region',
                'municipality',
                'scheduled_service',
                'gps_code',
                'iata_code',
                'local_code',
                'home_link',
                'wikipedia_link',
                'keywords'
            ],
            $this->csvPath
        );

        return $csvReader->lines();
    }
}