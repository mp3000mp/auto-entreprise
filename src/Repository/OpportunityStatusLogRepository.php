<?php

namespace App\Repository;

use App\Entity\OpportunityStatusLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OpportunityStatusLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpportunityStatusLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpportunityStatusLog[]    findAll()
 * @method OpportunityStatusLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpportunityStatusLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpportunityStatusLog::class);
    }

    // /**
    //  * @return TenderStatusLog[] Returns an array of TenderStatusLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TenderStatusLog
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
