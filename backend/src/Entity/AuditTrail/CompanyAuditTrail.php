<?php

namespace App\Entity\AuditTrail;

use App\Entity\Company;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CompanyAuditTrail extends AbstractAuditTrailEntity
{
    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Company $entity;

    public function getEntity(): Company
    {
        return $this->entity;
    }

    public function setEntity(Company $entity): void
    {
        $this->entity = $entity;
    }
}
