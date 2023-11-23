<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @return int[]
     */
    public function findDeletableIds(): array
    {
        $rsm = (new ResultSetMapping())
            ->addScalarResult('id', 'id');
        $sql = '
SELECT contact.id 
FROM contact
WHERE contact.id NOT IN (
    SELECT contact_id FROM opportunity_contact
)
        ';
        $q = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $q->getSingleColumnResult();
    }
}
