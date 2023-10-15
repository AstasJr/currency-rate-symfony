<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\FetchAndStoreCurrencies;

class FetchCurrenciesCommand extends Command
{
    protected static $defaultName = 'fetch:currencies';

    private $bus;
    private $logger;

    public function __construct(MessageBusInterface $bus, LoggerInterface $logger)
    {
        parent::__construct();

        $this->bus = $bus;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setDescription('Fetch currencies from cbr.ru and store them');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start fetching currencies from cbr.ru');

        $this->bus->dispatch(new FetchAndStoreCurrencies());

        $output->writeln('Finish fetching currencies from cbr.ru');

        return Command::SUCCESS;
    }
}
