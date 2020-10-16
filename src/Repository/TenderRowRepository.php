<?php

namespace App\Repository;

use App\Entity\TenderRow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TenderRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TenderRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TenderRow[]    findAll()
 * @method TenderRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenderRowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TenderRow::class);
    }

    // /**
    //  * @return TenderRow[] Returns an array of TenderRow objects
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
    public function findOneBySomeField($value): ?TenderRow
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
