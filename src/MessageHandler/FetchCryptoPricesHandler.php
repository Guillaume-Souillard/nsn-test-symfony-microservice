<?php

namespace App\MessageHandler;

use App\ApiProvider\CoinMarketCapProvider;
use App\ApiProvider\CoinGeckoProvider;
use App\Message\FetchCryptoPricesMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FetchCryptoPricesHandler
{
    public function __construct(
        private CoinMarketCapProvider $coinMarketCapProvider,
        private CoinGeckoProvider $coinGeckoProvider
    ) {}

    public function __invoke(FetchCryptoPricesMessage $message): void
    {
        $provider = $message->getProvider();

        if ($provider === 'coinmarketcap') {
            $this->coinMarketCapProvider->syncData();
        } elseif ($provider === 'coingecko') {
            $this->coinGeckoProvider->syncData();
        }
    }
}
