<?php

declare(strict_types=1);

namespace App\AirTraffic\DataImport;

use App\AirTraffic\DataImport\Mapper\SanitizedAirportSource;
use App\AirTraffic\Domain\Airport;
use App\AirTraffic\Domain\DataIntegrityException;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;

class AirportReader
{
    private array $errors;

    public function __construct(private array $data) {}

    public function airports(): \Generator
    {
        $this->errors = [];
        $mapper = (new MapperBuilder())->mapper();

        foreach ($this->data as $airportData) {
            $source = Source::iterable(new SanitizedAirportSource($airportData))->camelCaseKeys();

            try {
                yield $mapper->map(
                    Airport::class,
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