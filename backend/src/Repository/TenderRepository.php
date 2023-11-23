<?php

namespace App\Repository;

use App\Entity\Tender;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tender>
 */
class TenderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tender::class);
    }

    /**
     * @return int[]
     */
    public function findDeletableIds(): array
    {
        $rsm = (new ResultSetMapping())
            ->addScalarResult('id', 'id');
        $sql = '
SELECT tender.id 
FROM tender
WHERE tender.id NOT IN (
    SELECT tender_id FROM worked_time
)
        ';
        $q = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $q->getSingleColumnResult();
    }
}
