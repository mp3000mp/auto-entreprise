<?php

namespace App\Repository;

use App\Entity\WorkedTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkedTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkedTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkedTime[]    findAll()
 * @method WorkedTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkedTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkedTime::class);
    }

}
