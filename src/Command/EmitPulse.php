<?php

declare(strict_types=1);

namespace App\Command;

use App\Messages\PulseFactory as PulseService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:pulse', description: 'Sends messages to trigger application flow')]
final class EmitPulse extends Command implements SignalableCommandInterface
{
    private LockInterface $lock;
    private bool $aborting = false;

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LockFactory $lockFactory,
        private readonly PulseService $pulse
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Ignore locks and force execution');
        $this->addOption('time-limit', 't', InputOption::VALUE_REQUIRED, 'Quit after N seconds', 600);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);
        $quitAfter = (int) $input->getOption('time-limit');

        $this->lock = $this->lockFactory->createLock('lock://metronome', null);

        if (!$this->lock->acquire() && !$input->getOption('force')) {
            $ui->error('Another hearbeat process seems to be in progress, aborting.');

            return Command::FAILURE;
        }

        $ui->info('Starting application heartbeat...');

        $startTime = time();

        foreach ($this->pulse->beats() as $heartbeat) {
            $this->messageBus->dispatch($heartbeat);
            if ($this->aborting || time() - $startTime > $quitAfter) {
                break;
            }
        }

        $ui->success('... done.');

        $this->lock->release();

        return Command::SUCCESS;
    }

    public function getSubscribedSignals(): array
    {
        return [SIGINT, SIGTERM];
    }

    public function handleSignal(int $signal): void
    {
        $this->aborting = true;
    }
}