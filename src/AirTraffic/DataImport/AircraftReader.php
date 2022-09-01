<?php

declare(strict_types=1);

namespace App\AirTraffic\DataImport;

use App\AirTraffic\DataImport\Mapper\SanitizedAircraftSource;
use App\AirTraffic\Domain\Aircraft;
use App\AirTraffic\Domain\DataIntegrityException;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;

class AircraftReader
{
    private array $errors;

    public function __construct(private array $data) {}

    public function aircraft(): \Generator
    {
        $this->errors = [];
        $mapper = (new MapperBuilder())->mapper();

        foreach ($this->data as $aircraftData) {
            $source = Source::iterable(new SanitizedAircraftSource($aircraftData))->camelCaseKeys();

            try {
                yield $mapper->map(
                    Aircraft::class,
                    $source,
                );
            } catch (MappingError|DataIntegrityException $e) {
                $this->errors[] = $e;
            }
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}