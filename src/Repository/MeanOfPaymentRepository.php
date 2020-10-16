<?php

namespace App\Repository;

use App\Entity\MeanOfPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MeanOfPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeanOfPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeanOfPayment[]    findAll()
 * @method MeanOfPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeanOfPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeanOfPayment::class);
    }

    // /**
    //  * @return MeanOfPayment[] Returns an array of MeanOfPayment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MeanOfPayment
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
