<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

interface TrafficEvent
{
    public function getTransponderStatus(): TransponderStatus;

    public function getDisplayString(): string;
}