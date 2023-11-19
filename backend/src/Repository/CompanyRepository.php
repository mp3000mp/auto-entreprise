<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @return int[]
     */
    public function findDeletableIds(): array
    {
        $rsm = (new ResultSetMapping())
            ->addScalarResult('id', 'id');
        $sql = "
SELECT company.id 
FROM company
WHERE company.id NOT IN (
    SELECT company_id FROM contact
)
AND company.id NOT IN (
    SELECT company_id FROM opportunity
)
        ";
        $q = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $q->getSingleColumnResult();
    }
}
