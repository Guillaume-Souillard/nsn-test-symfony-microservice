<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\CryptoPriceProvider;
use App\State\CryptoPriceListProvider;
use Doctrine\DBAL\Types\Types;
use App\Repository\CryptoPriceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptoPriceRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            provider: CryptoPriceListProvider::class,
        ),
        new Get(
            uriVariables: ['ticker'],
            requirements: ['ticker', '[A-Z0-9]+'],
            provider: CryptoPriceProvider::class,
        )
    ]
)]
class CryptoPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ticker = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $provider = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct(string $ticker, int $price, string $provider)
    {
        $this->ticker = strtoupper($ticker);
        $this->setPrice($price);
        $this->provider = $provider;
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTicker(string $ticker): static
    {
        $this->ticker = strtoupper($ticker);
        return $this;
    }

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function setPrice(float $priceInDollars): static
    {
        $this->price = (int) round($priceInDollars * 100);
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price / 100;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): static
    {
        $this->provider = $provider;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
