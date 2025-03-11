<?php

namespace App\Tests\Unit\Entity;

use App\Entity\CryptoPrice;
use PHPUnit\Framework\TestCase;

class CryptoPriceTest extends TestCase
{
    public function testTickerIsUppercase()
    {
        $cryptoPrice = new CryptoPrice('btcusd', 10232, 'coingecko');

        $this->assertEquals('BTCUSD', $cryptoPrice->getTicker(), 'The ticker should be converted to uppercase');
    }

    public function testGettersReturnCorrectValues()
    {
        $cryptoPrice = new CryptoPrice('ethusd', 20000, 'coinmarketcap');

        $this->assertEquals('ETHUSD', $cryptoPrice->getTicker());
        $this->assertEquals(200.0, $cryptoPrice->getPrice());
        $this->assertEquals('coinmarketcap', $cryptoPrice->getProvider());
        $this->assertInstanceOf(\DateTimeInterface::class, $cryptoPrice->getUpdatedAt());
    }

    public function testUpdatedAtIsSetAutomatically()
    {
        $cryptoPrice = new CryptoPrice('XRPUSD', 500, 'coingecko');

        $this->assertNotNull($cryptoPrice->getUpdatedAt());
        $this->assertLessThanOrEqual(new \DateTime(), $cryptoPrice->getUpdatedAt(), 'The updatedAt timestamp should be recent');
    }

    public function testSettersModifyValuesCorrectly()
    {
        $cryptoPrice = new CryptoPrice('BTCUSD', 10232, 'coingecko');

        $cryptoPrice->setTicker('ETHUSD');
        $cryptoPrice->setPrice(20000);
        $cryptoPrice->setProvider('coinmarketcap');

        $this->assertEquals('ETHUSD', $cryptoPrice->getTicker());
        $this->assertEquals(20000, $cryptoPrice->getPrice());
        $this->assertEquals('coinmarketcap', $cryptoPrice->getProvider());
    }

    public function testUpdatedAtSetterWorks()
    {
        $cryptoPrice = new CryptoPrice('BTCUSD', 10232, 'coingecko');

        $newDate = new \DateTime('-1 day');
        $cryptoPrice->setUpdatedAt($newDate);

        $this->assertEquals($newDate, $cryptoPrice->getUpdatedAt(), 'The updatedAt setter should correctly modify the timestamp');
    }

    public function testPriceConversion()
    {
        $cryptoPrice = new CryptoPrice('BTCUSD', 12345, 'coingecko');

        $this->assertEquals(123.45, $cryptoPrice->getPrice(), 'The price should be correctly converted from cents to dollars');
    }

    public function testPriceSetterStoresAsCents()
    {
        $cryptoPrice = new CryptoPrice('BTCUSD', 0, 'coingecko');

        $cryptoPrice->setPrice(199.99);

        $this->assertEquals(19999, $cryptoPrice->getPrice() * 100, 'The price should be stored in cents');
    }

    public function testSetTickerConvertsToUppercase()
    {
        $cryptoPrice = new CryptoPrice('BTCUSD', 10232, 'coingecko');

        $cryptoPrice->setTicker('ethusd');

        $this->assertEquals('ETHUSD', $cryptoPrice->getTicker(), 'The ticker should always be stored in uppercase');
    }

    public function testUpdatedAtDoesNotChangeIfNotSet()
    {
        $cryptoPrice = new CryptoPrice('BTCUSD', 10232, 'coingecko');

        $initialUpdatedAt = $cryptoPrice->getUpdatedAt();
        sleep(1);
        $this->assertEquals($initialUpdatedAt, $cryptoPrice->getUpdatedAt(), 'updatedAt should not change unless explicitly set');
    }
}
