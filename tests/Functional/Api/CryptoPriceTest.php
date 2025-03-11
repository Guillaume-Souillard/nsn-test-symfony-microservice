<?php

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\CryptoPriceFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CryptoPriceTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testGetCryptoPrice(): void
    {
        CryptoPriceFactory::createMany(5, ['ticker' => 'BTCUSD', 'price' => 45000 * 100]);

        $client = static::createClient();
        $response = $client->request('GET', '/api/crypto_prices/BTCUSD');

        $this->assertResponseIsSuccessful();

        $responseData = $response->toArray();
        $this->assertEquals('BTCUSD', $responseData['ticker']);
        $this->assertEquals(45000.00, $responseData['average_price']);
    }

    public function testCryptoPriceNotFound(): void
    {
        $client = static::createClient();

        $response = $client->request('GET', '/api/crypto_prices/FAKE');

        $this->assertResponseStatusCodeSame(404);

        $responseData = $response->toArray(false);
        $this->assertEquals(['errors' => ['ticker' => 'ticker not found']], $responseData);
    }
}
