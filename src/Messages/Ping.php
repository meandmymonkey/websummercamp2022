<?php

declare(strict_types=1);

namespace App\Messages;

class Ping
{
    public function __toString(): string
    {
        return 'ping';
    }
}