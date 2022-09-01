<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain\TransponderStatusChecker;

use App\AirTraffic\Domain\TrafficEvent;
use App\AirTraffic\Domain\TrafficEvent\Touchdown;
use App\AirTraffic\Domain\TransponderStatus;

final class IsTouchdown extends AbstractChecker
{
    public function check(TransponderStatus $newStatus, TransponderStatus $oldStatus): ?TrafficEvent
    {
        if ($oldStatus->airborne === true && $newStatus->airborne === false) {
            return new Touchdown(
                $newStatus,
                $this->getAircraftForTransponder($newStatus),
                $this->getAirportAtAircraft($newStatus)
            );
        }

        return null;
    }
}