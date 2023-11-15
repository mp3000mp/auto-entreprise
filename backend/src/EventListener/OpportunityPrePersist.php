<?php

namespace App\EventListener;

use App\Entity\Opportunity;
use App\Entity\OpportunityStatus;
use App\Entity\OpportunityStatusLog;
use App\Enum\OpportunityStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(Events::prePersist, 'prePersist', entity: Opportunity::class)]
#[AsEntityListener(Events::preUpdate, 'preUpdate', entity: Opportunity::class)]
class OpportunityPrePersist
{
    public function __construct(private Security $security, private EntityManagerInterface $em)
    {
    }

    public function prePersist(Opportunity $opportunity, PrePersistEventArgs $args): void
    {
        if (null === $opportunity->getStatus()) {
            $status = $this->em->getRepository(OpportunityStatus::class)->findOneBy(['label' => OpportunityStatusEnum::TRACKED]);
            $opportunity->setStatus($status);
        }
        $this->createLog($opportunity);
    }

    public function preUpdate(Opportunity $opportunity, PreUpdateEventArgs $args): void
    {
        if ($args->hasChangedField('status')) {
            $this->createLog($opportunity);
        }
    }

    private function createLog(Opportunity $opportunity): void
    {
        $log = (new OpportunityStatusLog())
            ->setStatus($opportunity->getStatus())
            ->setOpportunity($opportunity)
            ->setCreatedAt(new \DateTime())
            ->setCreatedBy($this->security->getUser());
        $this->em->persist($log);
    }
}
