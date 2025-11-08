<?php

namespace Beerfranz\LicenceClientBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Beerfranz\LicenceClientBundle\Entity\LicenceUserMessage;

class LicenceUserMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenceUserMessage::class);
    }
}
