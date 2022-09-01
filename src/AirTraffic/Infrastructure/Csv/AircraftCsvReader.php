<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Csv;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AircraftCsvReader
{
    private array $errors;

    public function __construct(#[Autowire('%kernel.project_dir%/data/aircraft.csv')] private string $csvPath) {}

    public function entries(): \Generator
    {
        $csvReader = new CsvReader(
            [
                'icao24',
                'registration',
                'manufacturerIcao',
                'manufacturerName',
                'model',
                'typeCode',
                'serialNumber',
                'lineNumber',
                'icaoAircraftType',
                'operator',
                'operatorCallsign',
                'operatorIcao',
                'operatorIata',
                'owner',
                'testReg',
                'registered',
                'regUntil',
                'status',
                'built',
                'firstFlightDate',
                'seatConfiguration',
                'engines',
                'modes',
                'adsb',
                'acars',
                'notes',
                'categoryDescription',
            ],
            $this->csvPath,
            2
        );

        return $csvReader->lines();
    }
}