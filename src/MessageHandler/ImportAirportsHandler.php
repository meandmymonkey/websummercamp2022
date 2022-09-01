<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\AirTraffic\AirportUpdater;
use App\AirTraffic\DataImport\AirportReader;
use App\Messages\AirportData;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ImportAirportsHandler
{
    public function __construct(private readonly AirportUpdater $airportUpdater) {}

    public function __invoke(AirportData $airportData): void
    {
        $reader = new AirportReader($airportData->data);

        ($this->airportUpdater)(...$reader->airports());
    }
}