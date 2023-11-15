<?php

namespace App\EventListener;

use App\Entity\Tender;
use App\Entity\TenderStatus;
use App\Entity\TenderStatusLog;
use App\Enum\TenderStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(Events::prePersist, 'prePersist', entity: Tender::class)]
#[AsEntityListener(Events::preUpdate, 'preUpdate', entity: Tender::class)]
class TenderPrePersist
{
    public function __construct(private Security $security, private EntityManagerInterface $em)
    {
    }

    public function prePersist(Tender $tender, PrePersistEventArgs $args): void
    {
        if (null === $tender->getStatus()) {
            $status = $this->em->getRepository(TenderStatus::class)->findOneBy(['label' => TenderStatusEnum::ONGOING]);
            $tender->setStatus($status);
        }
        $this->createLog($tender);
    }

    public function preUpdate(Tender $tender, PreUpdateEventArgs $args): void
    {
        if ($args->hasChangedField('status')) {
            $this->createLog($tender);
        }
    }

    private function createLog(Tender $tender): void
    {
        $log = (new TenderStatusLog())
            ->setStatus($tender->getStatus())
            ->setTender($tender)
            ->setCreatedAt(new \DateTime())
            ->setCreatedBy($this->security->getUser());
        $this->em->persist($log);
    }
}
