<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

enum PositionType: string
{
    case Transponder = 'transponder';
    case Airport = 'airport';
}