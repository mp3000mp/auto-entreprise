<?php

namespace App\Entity\AuditTrail;

use App\Entity\Company;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CompanyAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Company
     */
    private $entity;

    public function getEntity(): Company
    {
        return $this->entity;
    }

    public function setEntity(Company $entity): void
    {
        $this->entity = $entity;
    }
}
