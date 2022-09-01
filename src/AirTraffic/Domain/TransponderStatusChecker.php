<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'app.transponder_status_checker')]
interface TransponderStatusChecker
{
    public function check(TransponderStatus $newStatus, TransponderStatus $oldStatus): ?TrafficEvent;
}