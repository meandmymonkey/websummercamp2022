<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Http;

use App\AirTraffic\Domain\DataSource\TransponderStatusStream;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\Finder\Finder;

#[When(env: 'dev')]
#[When(env: 'test')]
class ReplayTransponderStatus implements TransponderStatusStream
{
    private int $index;
    private int $numRecords;

    public function __construct(#[Autowire('%kernel.project_dir%/data/transponder')] private string $dataDir, private readonly LoggerInterface $logger)
    {
        $files = Finder::create()->in($this->dataDir)->name('*.json')->files();
        $this->numRecords = count($files);
        $this->index = 0;
    }

    public function latestData(): iterable
    {
        $path = $this->dataDir.sprintf('/%03s.json', $this->index);
        $data = \json_decode(file_get_contents($path), true);

        foreach ($data['states'] as $state) {
            array_unshift($state, $data['time']);

            yield $state;
        }

        $this->index++;

        if ($this->index >= $this->numRecords) {
            $this->index = 0;
        }

        $this->logger->alert('Replay file '.$path);
    }
}