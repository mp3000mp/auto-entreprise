<?php

namespace App\Entity\AuditTrail;

use App\Entity\Contact;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ContactAuditTrail extends AbstractAuditTrailEntity
{
    #[ORM\ManyToOne(targetEntity: Contact::class)]
    #[ORM\JoinColumn]
    private Contact $entity;

    public function getEntity(): Contact
    {
        return $this->entity;
    }

    public function setEntity(Contact $entity): void
    {
        $this->entity = $entity;
    }
}
