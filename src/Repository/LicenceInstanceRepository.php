<?php

namespace Beerfranz\LicenceClientBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Beerfranz\LicenceClientBundle\Entity\LicenceInstance;

class LicenceInstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenceInstance::class);
    }

    /**
     * Returns the singleton record or null
     */
    public function findSingleton(): ?LicenceInstance
    {
        return $this->createQueryBuilder('c')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
