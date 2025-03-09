<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CryptoPriceProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        $ticker = $uriVariables['ticker'] ?? null;

        $query = $this->entityManager->createQuery("
            SELECT c.ticker, AVG(c.price) as average_price
            FROM App\Entity\CryptoPrice c
            WHERE c.ticker = :ticker
            GROUP BY c.ticker
        ")->setParameter('ticker', $ticker);

        $result = $query->getOneOrNullResult();

        if (!$result) {
            return new JsonResponse([
                'errors' => [
                    'ticker' => 'ticker not found',
                ]
            ], 404);
        }

        return new JsonResponse([
            'ticker' => $result['ticker'],
            'average_price' => round($result['average_price'] / 100, 2)
        ]);
    }
}
