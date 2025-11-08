<?php

namespace Beerfranz\LicenceClientBundle\Entity;

use Beerfranz\LicenceClientBundle\Doctrine\LicenceListener;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: \Beerfranz\LicenceClientBundle\Repository\LicenceUserMessageRepository::class)]
class LicenceUserMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $refId = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $message = null;

    #[ORM\Column(type: Types::STRING, length: 30, nullable: false)]
    private ?string $level = 'info';

    // TODO: audiance target, acknowledge/response

    public function __construct(string $refId, string $message)
    {
        $this->refId = $refId;
        $this->message = $message;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRefId()
    {
        return $this->refId;
    }

    public function setRefId($id): self
    {
        $this->refId = $id;
        return $this;
    }

    public function getMessage(): string
    {
    	return $this->message;
    }

    public function getLevel(): string
    {
    	return $this->level;
    }
}
