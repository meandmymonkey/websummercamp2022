<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Repository;

use App\AirTraffic\DataImport\Mapper\SanitizedAircraftSource;
use App\AirTraffic\Domain\Aircraft;
use App\AirTraffic\Domain\DataIntegrityException;
use App\AirTraffic\Domain\DataSource\AircraftRepository;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ElasticsearchException;

class ElasticSearchAircraftRepository implements AircraftRepository
{
    private const ES_INDEX = 'aircraft';

    public function __construct(private Client $elasticsearch) {}

    public function put(...$aircraftList): void
    {
        $params = ['body' => []];

        /** @var Aircraft $aircraft */
        foreach ($aircraftList as $aircraft) {
            $params['body'][] = [
                'index' => [
                    '_index' => self::ES_INDEX,
                    '_id' => $aircraft->icao24
                ]
            ];

            $params['body'][] = [
                'icao24' => $aircraft->icao24,
                'registration' => $aircraft->registration,
                'manufacturerIcao' => $aircraft->manufacturerIcao,
                'manufacturerName' => $aircraft->manufacturerName,
                'model' => $aircraft->model,
                'typeCode' => $aircraft->typeCode,
                'serialNumber' => $aircraft->serialNumber,
                'lineNumber' => $aircraft->lineNumber,
                'icaoAircraftType' => $aircraft->icaoAircraftType,
                'operator' => $aircraft->operator,
                'operatorCallsign' => $aircraft->operatorCallsign,
                'operatorIcao' => $aircraft->operatorIcao,
                'operatorIata' => $aircraft->operatorIata,
                'owner' => $aircraft->owner,
                'registered' => $aircraft->registered?->getTimestamp(),
                'regUntil' => $aircraft->regUntil?->getTimestamp(),
                'status' => $aircraft->status,
                'built' => $aircraft->built?->getTimestamp(),
                'firstFlightDate' => $aircraft->firstFlightDate?->getTimestamp(),
                'seatConfiguration' => $aircraft->seatConfiguration,
                'engines' => $aircraft->engines,
                'notes' => $aircraft->notes,
                'categoryDescription' => $aircraft->categoryDescription,
                'updatedAt' => $aircraft->updatedAt->getTimestamp()
            ];
        }

        $this->elasticsearch->bulk($params);

        // TODO: check responses
    }

    public function get(string $icao24): ?Aircraft
    {
        try {
            $result = $this->elasticsearch->get(['id' => $icao24, 'index' => self::ES_INDEX]);
        } catch (ElasticsearchException $e) {
            // TODO: error handling?
            return null;
        }

        $source = Source::iterable(json_decode($result->asString(), true)['_source'])->camelCaseKeys();
        $mapper = (new MapperBuilder())->mapper();

        try {
            $aircraft = $mapper->map(
                Aircraft::class,
                $source,
            );
        } catch (MappingError|DataIntegrityException $e) {
            // TODO: error handling?

            return null;
        }

        return $aircraft;
    }

    public function find(string $phrase): iterable
    {

    }

    public function clear(): void
    {

    }
}