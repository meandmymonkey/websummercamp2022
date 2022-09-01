<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Repository;

use App\AirTraffic\Domain\Airport;
use App\AirTraffic\Domain\DataIntegrityException;
use App\AirTraffic\Domain\DataSource\AirportRepository;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ElasticsearchException;

class ElasticSearchAirportRepository implements AirportRepository
{
    private const ES_INDEX = 'airports';

    public function __construct(private Client $elasticsearch) {}

    public function put(...$airports): void
    {
        $params = ['body' => []];

        foreach ($airports as $airport) {
            $params['body'][] = [
                'index' => [
                    '_index' => self::ES_INDEX,
                    '_id' => $airport->icaoCode
                ]
            ];

            $params['body'][] = [
                'icaoCode' => $airport->icaoCode,
                'type' => $airport->type,
                'name' => $airport->name,
                'location' => [
                    'latitude' => $airport->location->latitude,
                    'longitude' => $airport->location->longitude,
                    'elevation' => $airport->location->elevation
                ],
                'continent' => $airport->continent,
                'isoCountry' => $airport->isoCountry,
                'isoRegion' => $airport->isoRegion,
                'municipality' => $airport->municipality,
                'scheduledService' => $airport->scheduledService,
                'gpsCode' => $airport->gpsCode,
                'iataCode' => $airport->iataCode,
                'localCode' => $airport->localCode,
                'webHomepage' => $airport->webHomepage,
                'webWikipedia' => $airport->webWikipedia,
                'keywords' => $airport->keywords,
                'updatedAt' => $airport->updatedAt->getTimestamp()
            ];
        }

        $this->elasticsearch->bulk($params);

        // TODO: check responses
    }

    public function get(string $icaoCode): ?Airport
    {
        try {
            $result = $this->elasticsearch->get(['id' => $icaoCode, 'index' => self::ES_INDEX]);
        } catch (ElasticsearchException $e) {
            // TODO: error handling?
            return null;
        }

        $source = Source::iterable(json_decode($result->asString(), true)['_source'])->camelCaseKeys();
        $mapper = (new MapperBuilder())->mapper();

        try {
            $aircraft = $mapper->map(
                Airport::class,
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