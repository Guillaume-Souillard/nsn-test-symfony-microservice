<?php

namespace App\Story;

use App\Factory\CryptoPriceFactory;
use Zenstruck\Foundry\Story;

final class DefaultCryptoPriceStory extends Story
{
    public function build(): void
    {
        CryptoPriceFactory::createMany(5, ['ticker' => 'BTC']);
        CryptoPriceFactory::createMany(3, ['ticker' => 'ETH']);
    }
}
