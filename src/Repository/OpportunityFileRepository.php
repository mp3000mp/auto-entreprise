<?php

namespace App\Repository;

use App\Entity\OpportunityFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OpportunityFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpportunityFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpportunityFile[]    findAll()
 * @method OpportunityFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpportunityFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpportunityFile::class);
    }

    // /**
    //  * @return OpportunityFile[] Returns an array of OpportunityFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OpportunityFile
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
