<?php

declare(strict_types=1);

namespace App\AirTraffic\DataImport;

use App\AirTraffic\DataImport\Mapper\SanitizedTransponderStatusSource;
use App\AirTraffic\Domain\DataIntegrityException;
use App\AirTraffic\Domain\DataSource\TransponderStatusStream;
use App\AirTraffic\Domain\TransponderStatus;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;

class TransponderStatusReader
{
    private array $errors;

    public function __construct(private TransponderStatusStream $api) {}

    public function transponderStatus(): \Generator
    {
        $this->errors = [];
        $mapper = (new MapperBuilder())->mapper();

        foreach ($this->api->latestData() as $transponderData) {
            $source = Source::iterable(new SanitizedTransponderStatusSource($transponderData))->camelCaseKeys();

            try {
                yield $mapper->map(
                    TransponderStatus::class,
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