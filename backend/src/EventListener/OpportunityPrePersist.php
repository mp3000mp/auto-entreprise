<?php

namespace App\EventListener;

use App\Entity\Opportunity;
use App\Entity\OpportunityStatus;
use App\Enum\OpportunityStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(Events::prePersist)]
class OpportunityPrePersist
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Opportunity) {
            return;
        }

        $em = $args->getObjectManager();
        if (null === $entity->getId() && null === $entity->getStatus()) {
            $status = $em->getRepository(OpportunityStatus::class)->findOneBy(['label' => OpportunityStatusEnum::TRACKED]);
            $entity->setStatus($status);
        }
    }
}
