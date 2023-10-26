<?php

namespace App\Command;

use App\Jobs\FetchCurrencyData;
use App\Message\FetchCurrencyRateMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class FetchCurrencyRateCommand extends Command
{
    protected static $defaultName = 'fetch:currency-rate';

    private $bus;

    private $logger;

    public function __construct(MessageBusInterface $bus, LoggerInterface $appLogger)
    {
        parent::__construct();

        $this->bus = $bus;
        $this->logger = $appLogger;
    }

    protected function configure(): void
    {
        $this->setDescription('Fetch currencies rates');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $this->bus->dispatch(new FetchCurrencyRateMessage($date));
            }
            $output->writeln('Finish fetching currency rate');
        } catch (\Exception $e) {
            $this->logger->error("Произошла ошибка: " . $e->getMessage());
            $this->logger->error($e->getMessage(), ['exception' => $e]);
        }

        return Command::SUCCESS;
    }
}