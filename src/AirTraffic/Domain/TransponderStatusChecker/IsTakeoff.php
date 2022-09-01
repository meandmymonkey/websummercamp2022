<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain\TransponderStatusChecker;

use App\AirTraffic\Domain\TrafficEvent;
use App\AirTraffic\Domain\TrafficEvent\Takeoff;
use App\AirTraffic\Domain\TransponderStatus;

final class IsTakeoff extends AbstractChecker
{
    public function check(TransponderStatus $newStatus, TransponderStatus $oldStatus): ?TrafficEvent
    {
        if ($oldStatus->airborne === false && $newStatus->airborne === true) {
            return new Takeoff(
                $newStatus,
                $this->getAircraftForTransponder($newStatus),
                $this->getAirportAtAircraft($newStatus)
            );
        }

        return null;
    }
}