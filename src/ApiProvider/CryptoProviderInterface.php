<?php

namespace App\ApiProvider;

interface CryptoProviderInterface
{
    public function fetchAllPrices(): array;

    public function syncData(): void;

    public function getProviderName(): string;
}
