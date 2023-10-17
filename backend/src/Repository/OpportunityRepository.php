<?php

namespace App\Repository;

use App\Entity\Opportunity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Opportunity>
 */
class OpportunityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opportunity::class);
    }

    /**
     * get last opened opportunities.
     *
     * @return Opportunity[]
     */
    public function findWelcomeDashboard(): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.tenders', 't')
            ->innerJoin('t.tenderRows', 'tr')
            ->where('t.status IN (2,3)')
            ->orderBy('t.sentAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
