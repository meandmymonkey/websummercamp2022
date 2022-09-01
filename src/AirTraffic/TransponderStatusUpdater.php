<?php

declare(strict_types=1);

namespace App\AirTraffic;

use App\AirTraffic\Domain\DataSource\AircraftRepository;
use App\AirTraffic\Domain\DataSource\TransponderStatusRepository;
use App\AirTraffic\Domain\TrafficEvent\NewTransponder;
use App\AirTraffic\Domain\TransponderStatus;
use App\AirTraffic\Domain\TransponderStatusChecker;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class TransponderStatusUpdater
{
    /**
     * @param TransponderStatusChecker[] $statusCheckers
     */
    public function __construct(
        private readonly TransponderStatusRepository $transponderStatusRepository,
        private readonly AircraftRepository $aircraftRepository,
        #[TaggedIterator(tag: 'app.transponder_status_checker')] private readonly iterable $statusCheckers
    ) {}

    public function __invoke(TransponderStatus ...$transponderStatus): \Generator
    {
        foreach ($transponderStatus as $newTransponderStatus) {
            $oldTransponderStatus = $this->transponderStatusRepository->get($newTransponderStatus->icao24);
            $this->transponderStatusRepository->put($newTransponderStatus);

            if ($oldTransponderStatus === null) {
                yield new NewTransponder(
                    $newTransponderStatus,
                    $this->aircraftRepository->get($newTransponderStatus->icao24)
                );

                continue;
            }

            foreach ($this->statusCheckers as $statusChecker) {
                $event = $statusChecker->check($newTransponderStatus, $oldTransponderStatus);
                if ($event !== null) {
                    yield $event;
                }
            }
        }
    }
}