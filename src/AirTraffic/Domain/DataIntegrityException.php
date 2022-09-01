<?php

declare(strict_types=1);

namespace App\AirTraffic\Domain;

class DataIntegrityException extends \RuntimeException
{
    private function __construct(string $message, private mixed $payload = null)
    {
        parent::__construct($message);
    }

    public static function forInvalidCsvLine(array $lineData, int|null $line = null): self
    {
        return new static(\sprintf('Invalid CSV Data in line %s.', $line ==! null ?? 'unkown'), $lineData);
    }

    public static function forInvalidLatitude(float $value): self
    {
        return new static(\sprintf('Invalid latitude %s.', $value));
    }

    public static function forInvalidLongitude(float $value): self
    {
        return new static(\sprintf('Invalid longitude %s.', $value));
    }

    public static function forInvalidElevation(float $value): self
    {
        return new static(\sprintf('Invalid elevation %s.', $value));
    }

    public static function forInvalidComparisonTarget(string $targetId): self
    {
        return new static('Cannot compare object state, wrong ID or ICAO code', $targetId);
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }
}