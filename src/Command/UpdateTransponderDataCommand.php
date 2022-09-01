<?php

declare(strict_types=1);

namespace App\Command;

use App\AirTraffic\DataImport\TransponderStatusReader;
use App\Messages\IncomingTransponderStatus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:read:transponders')]
class UpdateTransponderDataCommand extends Command
{
    public function __construct(
        private readonly TransponderStatusReader $transponderStatusReader,
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            foreach ($this->transponderStatusReader->transponderStatus() as $transponderStatus) {
                $this->messageBus->dispatch(new IncomingTransponderStatus($transponderStatus));
            }

            sleep(10);
        }

        return Command::SUCCESS;
    }
}