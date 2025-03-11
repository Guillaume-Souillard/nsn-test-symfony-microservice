<?php

namespace App\Tests\Unit\ApiProvider;

use App\ApiProvider\CoinGeckoProvider;
use App\Repository\CryptoPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CoinGeckoProviderTest extends TestCase
{
    private CoinGeckoProvider $coinGeckoProvider;
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

        $this->coinGeckoProvider = new CoinGeckoProvider(
            $this->httpClient,
            $this->entityManager,
            $this->cryptoPriceRepository,
            'https://api.coingecko.com/api/v3',
            'dummy_api_key'
        );
    }

    public function testFetchAllPrices(): void
    {
        $mockData = [
            [
                "symbol" => "btc",
                "current_price" => 60000
            ],
            [
                "symbol" => "eth",
                "current_price" => 4000
            ]
        ];

        $this->response
            ->method('toArray')
            ->willReturn($mockData);

        $prices = $this->coinGeckoProvider->fetchAllPrices();

        $this->assertCount(2, $prices);
        $this->assertEquals('BTCUSD', $prices[0]['ticker']);
        $this->assertEquals(60000, $prices[0]['price']);
        $this->assertEquals('ETHUSD', $prices[1]['ticker']);
        $this->assertEquals(4000, $prices[1]['price']);
    }

    public function testGetProviderName(): void
    {
        $this->assertEquals('coingecko', $this->coinGeckoProvider->getProviderName());
    }
}
