<?php

namespace App\Entity\AuditTrail;

use App\Entity\Opportunity;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class OpportunityAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Opportunity")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Opportunity
     */
    private $entity;

    public function getEntity(): Opportunity
    {
        return $this->entity;
    }

    public function setEntity(Opportunity $entity): void
    {
        $this->entity = $entity;
    }
}
