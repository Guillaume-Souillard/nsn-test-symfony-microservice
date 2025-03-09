<?php
namespace App\Tests\Repository;

use App\Entity\CryptoPrice;
use App\Repository\CryptoPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CryptoPriceRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private CryptoPriceRepository $cryptoPriceRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->cryptoPriceRepository = $this->entityManager->getRepository(CryptoPrice::class);

        $this->entityManager->createQuery('DELETE FROM App\Entity\CryptoPrice')->execute();
    }

    public function testUpdateOrCreateCreatesNewEntry(): void
    {
        $this->cryptoPriceRepository->updateOrCreate('BTCUSD', 50000, 'coingecko');

        $storedCrypto = $this->cryptoPriceRepository->findOneBy([
            'ticker' => 'BTCUSD',
            'provider' => 'coingecko'
        ]);

        $this->assertNotNull($storedCrypto);
        $this->assertEquals(500.0, $storedCrypto->getPrice());
    }

    public function testUpdateOrCreateUpdatesExistingEntry(): void
    {
        $this->cryptoPriceRepository->updateOrCreate('BTCUSD', 40000, 'coingecko');

        $this->cryptoPriceRepository->updateOrCreate('BTCUSD', 55000, 'coingecko');

        $storedCrypto = $this->cryptoPriceRepository->findOneBy([
            'ticker' => 'BTCUSD',
            'provider' => 'coingecko'
        ]);

        $this->assertNotNull($storedCrypto);
        $this->assertEquals(55000, $storedCrypto->getPrice());
    }
}
