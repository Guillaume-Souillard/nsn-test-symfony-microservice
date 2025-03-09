<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CryptoPriceListProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        $page = max(1, (int) ($context['filters']['page'] ?? 1));
        $itemsPerPage = max(1, (int) ($context['filters']['itemsPerPage'] ?? 100));
        $offset = ($page - 1) * $itemsPerPage;

        $query = $this->entityManager->createQuery("
            SELECT c.ticker, AVG(c.price) as average_price
            FROM App\Entity\CryptoPrice c
            GROUP BY c.ticker
        ")
            ->setFirstResult($offset)
            ->setMaxResults($itemsPerPage);

        $results = $query->getResult();

        $totalQuery = $this->entityManager->createQuery("
            SELECT COUNT(DISTINCT c.ticker) 
            FROM App\Entity\CryptoPrice c
        ");

        $totalItems = (int) $totalQuery->getSingleScalarResult();

        return new JsonResponse([
            'total' => (int) $totalItems,
            'current_page' => (int) $page,
            'items_per_page' => (int) $itemsPerPage,
            'total_pages' => (int) ceil($totalItems / $itemsPerPage),
            'items' => array_map(fn($row) => [
                'ticker' => $row['ticker'],
                'average_price' => round($row['average_price'] / 100, 2)
            ], $results),
        ]);
    }
}
