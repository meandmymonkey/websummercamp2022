<?php

declare(strict_types=1);

namespace App\AirTraffic\Infrastructure\Csv;

class CsvReader
{
    private int $currentLine;

    public function __construct(
        private array $fields,
        private string $csvPath,
        private int $rowsToIgnore = 1
    ) {}

    public function lines(): \Generator
    {
        $file = fopen($this->csvPath, 'r');
        $this->currentLine = 1;

        while ($indexedData = fgetcsv($file, 1024, ",")) {
            if ($this->currentLine > $this->rowsToIgnore) {
                yield array_combine($this->fields, $indexedData);
            }

            $this->currentLine++;
        }

        fclose($file);
    }

    public function getCurrentLine(): int
    {
        return $this->currentLine;
    }
}