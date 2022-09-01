<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Http;

use App\AirTraffic\Domain\DataSource\TransponderStatusStream;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[When(env: 'prod')]
class LiveTransponderStatus implements TransponderStatusStream
{
    public function __construct(private HttpClientInterface $client, #[Autowire('%app.transponder_url%')] private string $transponderDataUrl) {}

    public function latestData(): iterable
    {
        $response = $this->client->request('GET', $this->transponderDataUrl);

        $data = \json_decode($response->getContent(), true);

        foreach ($data['states'] as $state) {
            array_unshift($state, $data['time']);

            yield $state;
        }
    }
}