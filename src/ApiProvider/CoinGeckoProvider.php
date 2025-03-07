<?php

namespace App\ApiProvider;

class CoinGeckoProvider extends AbstractCryptoProvider
{
    public function fetchAllPrices(): array
    {
        $url = "{$this->baseUrl}/coins/markets";
        $response = $this->httpClient->request('GET', $url, [
            'headers' => ['x-cg-demo-api-key' => $this->apiKey],
            'query' => [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'page' => 1,
                'precision' => 5
            ]
        ]);

        $data = $response->toArray();
        $formattedData = [];

        foreach ($data as $crypto) {
            $formattedData[] = [
                'ticker' => strtoupper($crypto['symbol']) . 'USD',
                'price' => $crypto['current_price'],
            ];
        }

        return $formattedData;
    }

    public function getProviderName(): string
    {
        return "coingecko";
    }
}
