<?php

namespace App\Tests\Unit\ApiProvider;

use App\ApiProvider\CoinMarketCapProvider;
use App\Repository\CryptoPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CoinMarketCapProviderTest extends TestCase
{
    private CoinMarketCapProvider $coinMarketCapProvider;
    private HttpClientInterface $httpClient;
    private ResponseInterface $response;
    private EntityManagerInterface $entityManager;
    private CryptoPriceRepository $cryptoPriceRepository;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->httpClient
            ->method('request')
            ->willReturn($this->response);

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->cryptoPriceRepository = $this->createMock(CryptoPriceRepository::class);

        $this->coinMarketCapProvider = new CoinMarketCapProvider(
            $this->httpClient,
            $this->entityManager,
            $this->cryptoPriceRepository,
            'https://pro-api.coinmarketcap.com/v1',
            'dummy_api_key'
        );
    }

    public function testFetchAllPrices(): void
    {
        $mockData = [
            'data' => [
                [
                    "symbol" => "BTC",
                    "quote" => [
                        "USD" => [
                            "price" => 60000
                        ]
                    ]
                ],
                [
                    "symbol" => "ETH",
                    "quote" => [
                        "USD" => [
                            "price" => 4000
                        ]
                    ]
                ]
            ]
        ];

        $this->response
            ->method('toArray')
            ->willReturn($mockData);

        $prices = $this->coinMarketCapProvider->fetchAllPrices();

        $this->assertCount(2, $prices);
        $this->assertEquals('BTCUSD', $prices[0]['ticker']);
        $this->assertEquals(60000, $prices[0]['price']);
        $this->assertEquals('ETHUSD', $prices[1]['ticker']);
        $this->assertEquals(4000, $prices[1]['price']);
    }

    public function testGetProviderName(): void
    {
        $this->assertEquals('coinmarketcap', $this->coinMarketCapProvider->getProviderName());
    }
}
