<?php

namespace App\EventListener;

use App\Entity\Tender;
use App\Entity\TenderStatus;
use App\Entity\TenderStatusLog;
use App\Enum\TenderStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(Events::prePersist, 'prePersist', entity: Tender::class)]
#[AsEntityListener(Events::preUpdate, 'preUpdate', entity: Tender::class)]
#[AsDoctrineListener(event: Events::postFlush)]
class TenderPrePersist
{
    /** @var TenderStatusLog[] */
    private array $pendingLogs = [];

    public function __construct(private Security $security)
    {
    }

    public function prePersist(Tender $tender, PrePersistEventArgs $args): void
    {
        $em = $args->getObjectManager();
        if (null === $tender->getStatus()) {
            $status = $em->getRepository(TenderStatus::class)->findOneBy(['label' => TenderStatusEnum::ONGOING]);
            $tender->setStatus($status);
        }
        $log = $this->buildLog($tender);
        $em->persist($log);
    }

    public function preUpdate(Tender $tender, PreUpdateEventArgs $args): void
    {
        if ($args->hasChangedField('status')) {
            $this->pendingLogs[] = $this->buildLog($tender);
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

    private function buildLog(Tender $tender): TenderStatusLog
    {
        return (new TenderStatusLog())
            ->setStatus($tender->getStatus())
            ->setTender($tender)
            ->setCreatedAt(new \DateTime())
            ->setCreatedBy($this->security->getUser());
    }
}
