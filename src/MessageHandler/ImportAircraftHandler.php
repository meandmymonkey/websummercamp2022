<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\AirTraffic\AircraftUpdater;
use App\AirTraffic\DataImport\AircraftReader;
use App\Messages\AircraftData;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ImportAircraftHandler
{
    public function __construct(private readonly AircraftUpdater $aircraftUpdater) {}

    public function __invoke(AircraftData $aircraftData): void
    {
        $reader = new AircraftReader($aircraftData->data);

        ($this->aircraftUpdater)(...$reader->aircraft());
    }
}