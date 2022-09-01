<?php

declare(strict_types=1);

namespace App\Command;

use App\AirTraffic\Infrastructure\Csv\AirportCsvReader;
use App\Message\AirportBatchData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:import:airports', description: 'Imports basic airport data')]
class ImportAirports extends Command
{
    public function __construct(private AirportCsvReader $airportCsvReader, private MessageBusInterface $messageBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $ui->info('Starting import...');
        $ui->progressStart();

        // ...

        $ui->progressFinish();
        $ui->success('... done.');

        return Command::SUCCESS;
    }
}