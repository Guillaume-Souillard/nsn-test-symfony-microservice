<?php

namespace App\Repository;

use App\Entity\CryptoPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CryptoPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CryptoPrice::class);
    }

    /**
     * Met à jour ou crée un CryptoPrice basé sur le ticker et le provider.
     */
    public function updateOrCreate(string $ticker, float $price, string $provider): CryptoPrice
    {
        $entityManager = $this->getEntityManager();

        $cryptoPrice = $this->findOneBy(['ticker' => $ticker, 'provider' => $provider]);

        if ($cryptoPrice) {
            $cryptoPrice->setPrice($price);
            $cryptoPrice->setUpdatedAt(new \DateTime());
        } else {
            $cryptoPrice = new CryptoPrice($ticker, $price, $provider);
            $entityManager->persist($cryptoPrice);
        }

        $entityManager->flush();

        return $cryptoPrice;
    }

}
