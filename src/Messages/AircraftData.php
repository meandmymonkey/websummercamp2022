<?php

declare(strict_types=1);

namespace App\Messages;

class AircraftData
{
    public function __construct(public readonly mixed $data) {}
}