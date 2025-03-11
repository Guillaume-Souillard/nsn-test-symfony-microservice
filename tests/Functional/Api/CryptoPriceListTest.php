<?php

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Story\DefaultCryptoPriceStory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CryptoPriceListTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testGetCryptoPriceList(): void
    {
        DefaultCryptoPriceStory::load();

        $client = static::createClient();

        $response = $client->request('GET', '/api/crypto_prices');

        $this->assertResponseIsSuccessful();

        $responseData = $response->toArray();

        $this->assertArrayHasKey('total', $responseData);
        $this->assertGreaterThan(0, $responseData['total']);
        $this->assertArrayHasKey('current_page', $responseData);
        $this->assertArrayHasKey('items_per_page', $responseData);
        $this->assertArrayHasKey('total_pages', $responseData);
        $this->assertArrayHasKey('items', $responseData);

        if (!empty($responseData['items'])) {
            $this->assertArrayHasKey('ticker', $responseData['items'][0]);
            $this->assertArrayHasKey('average_price', $responseData['items'][0]);
        }
    }
}

