<?php

namespace App\Entity\AuditTrail;

use App\Entity\Company;
use App\Entity\Contact;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ContactAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Contact
     */
    private $entity;

    public function getEntity(): Contact
    {
        return $this->entity;
    }

    public function setEntity(Contact $entity): void
    {
        $this->entity = $entity;
    }
}
