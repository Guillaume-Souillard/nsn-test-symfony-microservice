<?php

namespace App\Message;

class FetchCryptoPricesMessage
{
    public function __construct(
        private string $provider
    ) {}

    public function getProvider(): string
    {
        return $this->provider;
    }
}
