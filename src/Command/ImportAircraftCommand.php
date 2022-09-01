<?php

declare(strict_types=1);

namespace App\Command;

use App\AirTraffic\Infrastructure\Csv\AircraftCsvReader;
use App\Messages\AircraftData;
use App\Messages\AirportData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:import:aircraft', description: 'Imports aircraft data from CSV')]
class ImportAircraftCommand extends Command
{
    public function __construct(private AircraftCsvReader $aircraftCsvReader, private MessageBusInterface $messageBus, private LockFactory $lockFactory)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $lock = $this->lockFactory->createLock('lock://import-aircraft', 600);
        if (!$lock->acquire()) {
            $ui->error('Another import seems to be in progress, aborting.');

            return Command::FAILURE;
        }

        $ui->info('Starting import...');
        $ui->progressStart();

        $batchData = [];
        $batchSize = 100;
        foreach ($this->aircraftCsvReader->entries() as $aircraftData) {
            $batchData[] = $aircraftData;
            if (count($batchData) >= $batchSize) {
                $this->messageBus->dispatch(new AircraftData($batchData));
                $batchData = [];
                $ui->progressAdvance($batchSize);
            }
        }

        if (count($batchData)) {
            $this->messageBus->dispatch(new AircraftData($batchData));
        }

        $ui->progressFinish();
        $ui->success('... done.');

        $lock->release();

        return Command::SUCCESS;
    }
}