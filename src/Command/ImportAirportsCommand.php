<?php

declare(strict_types=1);

namespace App\Command;

use App\AirTraffic\Infrastructure\Csv\AirportCsvReader;
use App\Messages\AirportData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:import:airports')]
class ImportAirportsCommand extends Command
{
    public function __construct(
        private readonly AirportCsvReader $csvReader,
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $ui->info('Starting import...');
        $ui->progressStart();

        $batchData = [];
        $batchSize = 50;
        foreach ($this->csvReader->entries() as $airport) {
            $batchData[] = $airport;
            if (count($batchData) >= $batchSize) {
                $this->messageBus->dispatch(new AirportData($batchData));
                $batchData = [];
                $ui->progressAdvance($batchSize);
            }
        }

        $ui->progressFinish();
        $ui->success('... done.');

        return Command::SUCCESS;
    }

}