<?php

declare(strict_types=1);

namespace App\Messages;

class AirportData
{
    public function __construct(public readonly mixed $data) {}
}