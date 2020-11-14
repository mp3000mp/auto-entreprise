<?php

namespace App\EventListener;

use App\Entity\Company;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DoctrineSubscriber implements EventSubscriber
{

    /**
     * @var UserInterface
     */
    private $activeUser;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    private function getAuditTrailEntity($entity): ?AbstractAuditTrailEntity
    {
        $auditTrailEntityClass = str_replace('\\Entity\\','\\Entity\\AuditTrail\\',get_class($entity)).'AuditTrail';
        if(class_exists($auditTrailEntityClass)){
            $auditTrailEntity = new $auditTrailEntityClass();
            if($this->activeUser !== null){
                $auditTrailEntity->setUser($this->activeUser);
            }
            $auditTrailEntity->setDate(new \DateTime());
            $auditTrailEntity->setReason('todo');
            $auditTrailEntity->setEntity($entity);
            return $auditTrailEntity;
        }
        return null;
    }

    private function auditTrailInsert(UnitOfWork $uow, $entity): ?AbstractAuditTrailEntity
    {
        if($auditTrailEntity = $this->getAuditTrailEntity($entity)){
            $auditTrailEntity->setModifType(1);

            // filtre changeSet
            $changeSet = $uow->getEntityChangeSet($entity);
            $details = [];
            foreach ($changeSet as $property => $change){
                if(null !== $change[1]){
                    if(is_object($change[1])){
                        $details[$property] = $change[1]->getAuditTrailString() . ' (' . $change[1]->getId() . ')';
                    }else{
                        $details[$property] = $change[1];
                    }
                }
            }

            $auditTrailEntity->setDetails($details);
            return $auditTrailEntity;
        }
        return null;
    }

    private function auditTrailUpdate(UnitOfWork $uow, $entity): ?AbstractAuditTrailEntity
    {
        if($auditTrailEntity = $this->getAuditTrailEntity(get_class($entity))){
            $auditTrailEntity->setModifType(2);

            // filtre changeSet
            $changeSet = $uow->getEntityChangeSet($entity);
            $details = [];
            foreach ($changeSet as $property => $change){
                if(is_object($change[1])){
                    $details[$property] = $change[1]->getAuditTrailString() . ' (' . $change[1]->getId() . ')';
                }else{
                    $details[$property] = $change[1];
                }
            }

            $auditTrailEntity->setDetails($details);
            return $auditTrailEntity;
        }
    }

    private function auditTrailDelete(UnitOfWork $uow, $entity): ?AbstractAuditTrailEntity
    {
        echo 'Impossible de supprimer';
        exit();
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $nbPersist = 0;

        if($this->tokenStorage->getToken() !== null){
            $this->activeUser = $this->tokenStorage->getToken()->getUser();
        }
        $this->em = $args->getEntityManager();
        $uow = $this->em->getUnitOfWork();

        // inserts
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if($entityAuditTrail = $this->auditTrailInsert($uow, $entity)){
                dump($entityAuditTrail);
                $this->em->persist($entityAuditTrail);
                $nbPersist++;
            }
        }

        // updates
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if($entityAuditTrail = $this->auditTrailUpdate($uow, $entity)){
                dump($entityAuditTrail);
                $this->em->persist($entityAuditTrail);
                $nbPersist++;
            }
        }
        foreach ($uow->getScheduledCollectionUpdates() as $collection) {
            foreach ($collection as $entity) {
                if($entityAuditTrail = $this->auditTrailUpdate($uow, $entity)){
                    dump($entityAuditTrail);
                    $this->em->persist($entityAuditTrail);
                    $nbPersist++;
                }
            }
        }

        // deletes
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if($entityAuditTrail = $this->auditTrailDelete($uow, $entity)){
                dump($entityAuditTrail);
                $this->em->persist($entityAuditTrail);
                $nbPersist++;
            }
        }
        foreach ($uow->getScheduledCollectionDeletions() as $collection) {
            foreach ($collection as $entity) {
                if($entityAuditTrail = $this->auditTrailDelete($uow, $entity)){
                    dump($entityAuditTrail);
                    $this->em->persist($entityAuditTrail);
                    $nbPersist++;
                }
            }
        }

        if($nbPersist > 0){
            $this->em->getEventManager()->removeEventListener([Events::onFlush], $this);
            $this->em->flush();
            $this->em->getEventManager()->addEventListener([Events::onFlush], $this);
        }

    }
}
