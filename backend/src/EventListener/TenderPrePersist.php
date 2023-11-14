<?php

namespace App\EventListener;

use App\Entity\Tender;
use App\Entity\TenderStatus;
use App\Enum\TenderStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(Events::prePersist)]
class TenderPrePersist
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Tender) {
            return;
        }

        $em = $args->getObjectManager();
        if (null === $entity->getId() && null === $entity->getStatus()) {
            $status = $em->getRepository(TenderStatus::class)->findOneBy(['label' => TenderStatusEnum::ONGOING]);
            $entity->setStatus($status);
        }
    }
}
