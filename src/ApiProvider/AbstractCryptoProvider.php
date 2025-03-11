<?php

namespace App\ApiProvider;

use App\Repository\CryptoPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractCryptoProvider implements CryptoProviderInterface
{
    protected HttpClientInterface $httpClient;
    protected EntityManagerInterface $entityManager;
    protected CryptoPriceRepository $cryptoPriceRepository;
    protected string $baseUrl;

    protected string $apiKey;

    public function __construct(
        HttpClientInterface $httpClient,
        EntityManagerInterface $entityManager,
        CryptoPriceRepository $cryptoPriceRepository,
        string $baseUrl,
        string $apiKey
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->cryptoPriceRepository = $cryptoPriceRepository;
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    public function syncData(): void
    {
        $cryptos = $this->fetchAllPrices();

        foreach ($cryptos as $crypto) {
            $this->cryptoPriceRepository->updateOrCreate(
                $crypto['ticker'],
                $crypto['price'],
                $this->getProviderName()
            );
        }
    }
}

