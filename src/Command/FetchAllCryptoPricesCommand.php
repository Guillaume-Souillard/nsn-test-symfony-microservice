<?php

namespace App\Command;

use App\Message\FetchCryptoPricesMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:fetch-all-crypto-prices')]
class FetchAllCryptoPricesCommand extends Command
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Récupère et stocke les prix des cryptos depuis CoinMarketCap et CoinGecko.');
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Dispatching jobs to fetch crypto prices...");

        $this->messageBus->dispatch(new FetchCryptoPricesMessage('coinmarketcap'));
        $this->messageBus->dispatch(new FetchCryptoPricesMessage('coingecko'));

        $output->writeln("Jobs dispatched. Processing asynchronously.");
        return Command::SUCCESS;
    }
}

