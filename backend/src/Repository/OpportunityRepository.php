<?php

namespace App\Repository;

use App\Entity\Opportunity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
     * @return int[]
     */
    public function findDeletableIds(): array
    {
        $rsm = (new ResultSetMapping())
            ->addScalarResult('id', 'id');
        $sql = '
SELECT opportunity.id 
FROM opportunity
WHERE opportunity.id NOT IN (
    SELECT opportunity_id FROM tender
)
AND opportunity.id NOT IN (
    SELECT opportunity_id FROM worked_time
)
        ';
        $q = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $q->getSingleColumnResult();
    }

    /**
     * get last opened opportunities.
     *
     * @return Opportunity[]
     */
    public function findWelcomeDashboard(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.tenders', 't')
            ->orderBy('t.sentAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
