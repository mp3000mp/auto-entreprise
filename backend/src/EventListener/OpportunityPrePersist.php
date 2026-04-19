<?php

namespace App\EventListener;

use App\Entity\Opportunity;
use App\Entity\OpportunityStatus;
use App\Entity\OpportunityStatusLog;
use App\Enum\OpportunityStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(Events::prePersist, 'prePersist', entity: Opportunity::class)]
#[AsEntityListener(Events::preUpdate, 'preUpdate', entity: Opportunity::class)]
#[AsDoctrineListener(event: Events::postFlush)]
class OpportunityPrePersist
{
    /** @var OpportunityStatusLog[] */
    private array $pendingLogs = [];

    public function __construct(private Security $security)
    {
    }

    public function prePersist(Opportunity $opportunity, PrePersistEventArgs $args): void
    {
        $em = $args->getObjectManager();
        if (null === $opportunity->getStatus()) {
            $status = $em->getRepository(OpportunityStatus::class)->findOneBy(['label' => OpportunityStatusEnum::TRACKED]);
            $opportunity->setStatus($status);
        }
        $log = $this->buildLog($opportunity);
        $em->persist($log);
    }

    public function preUpdate(Opportunity $opportunity, PreUpdateEventArgs $args): void
    {
        if ($args->hasChangedField('status')) {
            $this->pendingLogs[] = $this->buildLog($opportunity);
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        if (!$this->pendingLogs) {
            return;
        }
        $em = $args->getObjectManager();
        foreach ($this->pendingLogs as $log) {
            $em->persist($log);
        }
        $this->pendingLogs = [];
        $em->flush();
    }

    private function buildLog(Opportunity $opportunity): OpportunityStatusLog
    {
        return (new OpportunityStatusLog())
            ->setStatus($opportunity->getStatus())
            ->setOpportunity($opportunity)
            ->setCreatedAt(new \DateTime())
            ->setCreatedBy($this->security->getUser());
    }
}
