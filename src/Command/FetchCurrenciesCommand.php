<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\FetchCurrenciesMessage;

class FetchCurrenciesCommand extends Command
{
    protected static $defaultName = 'fetch:currencies';

    public function __construct(private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetch currencies from cbr.ru and store them');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start fetching currencies from cbr.ru');

        $this->bus->dispatch(new FetchCurrenciesMessage());

        $output->writeln('Finish fetching currencies from cbr.ru');

        return Command::SUCCESS;
    }
}
