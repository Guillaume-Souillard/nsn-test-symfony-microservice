<?php

namespace App\Tests\Entity;

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
        $cryptoPrice = new CryptoPrice('ETHUSD', 20000, 'coinmarketcap');

        $this->assertEquals('ETHUSD', $cryptoPrice->getTicker());
        $this->assertEquals(20000, $cryptoPrice->getPrice());
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
}
