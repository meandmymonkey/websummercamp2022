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
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:import:airports')]
class ImportAirportsCommand extends Command
{
    public function __construct(
        private readonly AirportCsvReader $csvReader,
        private readonly MessageBusInterface $messageBus,
        private readonly LockFactory $lockFactory
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $lock = $this->lockFactory->createLock('lock://import-airports', 600);
        if (!$lock->acquire()) {
            $ui->error('Another import seems to be running, aborting');

            return Command::FAILURE;
        }

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

        if (count($batchData)) {
            $this->messageBus->dispatch(new AirportData($batchData));
        }

        $ui->progressFinish();
        $ui->success('... done.');

        $lock->release();

        return Command::SUCCESS;
    }

}