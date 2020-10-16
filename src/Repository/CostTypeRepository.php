<?php

namespace App\Repository;

use App\Entity\CostType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CostType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CostType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CostType[]    findAll()
 * @method CostType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CostTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CostType::class);
    }

    // /**
    //  * @return CostType[] Returns an array of CostType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CostType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
