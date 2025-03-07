<?php

namespace App\ApiProvider;

class CoinMarketCapProvider extends AbstractCryptoProvider
{
    public function fetchAllPrices(): array
    {
        $url = "{$this->baseUrl}/cryptocurrency/listings/latest";
        $response = $this->httpClient->request('GET', $url, [
            'headers' => ['X-CMC_PRO_API_KEY' => $this->apiKey],
            'query' => ['convert' => 'USD']
        ]);

        $data = $response->toArray()['data'];
        $formattedData = [];

        foreach ($data as $crypto) {
            $formattedData[] = [
                'ticker' => strtoupper($crypto['symbol']) . 'USD',
                'price' => $crypto['quote']['USD']['price'],
            ];
        }

        return $formattedData;
    }

    public function getProviderName(): string
    {
        return "coinmarketcap";
    }
}
