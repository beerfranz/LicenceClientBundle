<?php

namespace Beerfranz\LicenceClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: \Beerfranz\LicenceClientBundle\Repository\LicenceInstanceRepository::class)]
#[ORM\Table(name: 'check_version')]
class LicenceInstance
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: Types::STRING, length: 191, nullable: true)]
    private ?string $codeVersion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::STRING, length: 191, nullable: true)]
    private ?string $version = null;

    #[ORM\Column(type: Types::STRING, length: 191, nullable: true)]
    private ?string $product = null;

    #[ORM\Column(nullable: true)]
    private ?array $usages = null;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCodeVersion(): string
    {
        return $this->codeVersion;
    }

    public function setCodeVersion(string $codeVersion): self
    {
        $this->codeVersion = $codeVersion;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $datetime): self
    {
        $this->updatedAt = $datetime;
        return $this;
    }

    public function setUsages(array $usage): self
    {
        $this->usages = $usages;
        return $this;
    }

    public function getUsages(): ?array
    {
        return $this->usages;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

}
