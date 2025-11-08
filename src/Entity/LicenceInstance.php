<?php

namespace Beerfranz\LicenceClientBundle\Entity;

use Beerfranz\LicenceClientBundle\Doctrine\LicenceListener;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: \Beerfranz\LicenceClientBundle\Repository\LicenceInstanceRepository::class)]
#[ORM\EntityListeners([LicenceListener::class])]
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

    #[ORM\Column(nullable: true)]
    private ?array $configs = null;

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

    public function setConfigs(array $configs): self
    {
        $this->configs = $configs;
        return $this;
    }

    public function getConfigs(): ?array
    {
        if ($this->configs === null or count($this->configs) === 0)
        {
            return [
                "recurrings" => [
                    [
                        'trigger' => 'every',
                        'freq' => '1 month',
                        'transport' => 'async',
                        'message' => 'Beerfranz\LicenceClientBundle\Scheduler\Message',
                        'jitter' => 30,
                        'enabled' => true,
                    ]
                ]
            ];
        }
        return $this->configs;
    }

    public function commitMessage($id, int $returnCode = 0, string $returnMessage = 'Success'): self
    {
        $this->configs['messages'][$id]['returnCode'] = $returnCode;
        $this->configs['messages'][$id]['returnMessage'] = $returnMessage;
        return $this;
    }

}
