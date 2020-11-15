<?php

namespace App\EventListener;

use App\Service\AuditTrail\AbstractAuditTrailEntity;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;
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

    private function getChangeString($change): string
    {
        if (is_object($change)) {
            if(method_exists($change, 'getAuditTrailString')) {
                return $change->getAuditTrailString() . ' (' . $change->getId() . ')';
            }
            if($change instanceof DateTime) {
                return $change->format('Y-m-d H:i:s');
            }
            if(method_exists($change , '__toString')){
                return (string) $change;
            }
            echo 'Error: unexpected object';
            dump($change);
            exit();
        }
        return $change;
    }

    private function getAuditTrailEntity($entity): ?AbstractAuditTrailEntity
    {
        $auditTrailEntityClass = str_replace('\\Entity\\', '\\Entity\\AuditTrail\\', get_class($entity)).'AuditTrail';
        if (class_exists($auditTrailEntityClass)) {
            $auditTrailEntity = new $auditTrailEntityClass();
            if (null !== $this->activeUser) {
                $auditTrailEntity->setUser($this->activeUser);
            }
            $auditTrailEntity->setDate(new DateTime());
            $auditTrailEntity->setReason('todo');
            $auditTrailEntity->setEntity($entity);

            return $auditTrailEntity;
        }

        return null;
    }

    private function auditTrailInsert(UnitOfWork $uow, $entity): ?AbstractAuditTrailEntity
    {
        if ($auditTrailEntity = $this->getAuditTrailEntity($entity)) {
            $auditTrailEntity->setModifType(1);

            // filtre changeSet
            $changeSet = $uow->getEntityChangeSet($entity);
            $details = [];
            foreach ($changeSet as $property => $change) {
                if (!in_array($property, $entity->getFieldsToBeIgnored(), true)) {
                    if($change[1] !== null){
                        $details[$property] = $this->getChangeString($change[1]);
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
        if ($auditTrailEntity = $this->getAuditTrailEntity($entity)) {
            $auditTrailEntity->setModifType(2);

            // filtre changeSet
            $changeSet = $uow->getEntityChangeSet($entity);
            $details = [];
            foreach ($changeSet as $property => $change) {
                if (!in_array($property, $entity->getFieldsToBeIgnored(), true)) {
                    $before = $this->getChangeString($change[0]);
                    $after = $this->getChangeString($change[1]);
                    $details[$property] = [$before, $after];
                }
            }
            if (!empty($details)) {
                $auditTrailEntity->setDetails($details);

                return $auditTrailEntity;
            }
        }

        return null;
    }

    private function auditTrailDelete(UnitOfWork $uow, $entity): ?AbstractAuditTrailEntity
    {
        echo 'Impossible de supprimer';
        exit();
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $toBePersisted = [];

        if (null !== $this->tokenStorage->getToken()) {
            $this->activeUser = $this->tokenStorage->getToken()->getUser();
        }
        $this->em = $args->getEntityManager();
        $uow = $this->em->getUnitOfWork();

        // inserts
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $auditTrailEntity = $this->auditTrailInsert($uow, $entity);
            if ($auditTrailEntity !== null) {
                dump($toBePersisted);
                $toBePersisted[] = $auditTrailEntity;
            }
        }

        // updates
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $auditTrailEntity = $this->auditTrailUpdate($uow, $entity);
            if ($auditTrailEntity !== null) {
                dump($toBePersisted);
                $toBePersisted[] = $auditTrailEntity;
            }
        }
        foreach ($uow->getScheduledCollectionUpdates() as $collection) {
            $entity = $collection->getOwner();
            $mapping = $collection->getMapping();
            $before = $collection->getSnapShot();
            $auditTrailEntity = $this->getAuditTrailEntity($entity);
            if ($auditTrailEntity !== null) {
                $auditTrailEntity->setModifType(2);
                $details = [$mapping['fieldName'] => ['added' => [], 'removed' => []]];
                // added
                foreach ($collection as $assocEntity){
                    if(!in_array($assocEntity, $before)){
                        $details[$mapping['fieldName']]['added'][] = $this->getChangeString($assocEntity);
                    }
                }
                // removed
                foreach ($before as $assocEntity){
                    if(!$collection->contains($assocEntity)){
                        $details[$mapping['fieldName']]['removed'][] = $this->getChangeString($assocEntity);
                    }
                }
                if (!empty($details[$mapping['fieldName']]['added']) || $details[$mapping['fieldName']]['removed'] ) {
                    $auditTrailEntity->setDetails($details);
                    $toBePersisted[] = $auditTrailEntity;
                }
            }
        }

        // deletes
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($auditTrailEntity = $this->auditTrailDelete($uow, $entity)) {
                $toBePersisted[] = $auditTrailEntity;
            }
        }
        foreach ($uow->getScheduledCollectionDeletions() as $collection) {
            foreach ($collection as $entity) {
                if ($auditTrailEntity = $this->auditTrailDelete($uow, $entity)) {
                    $toBePersisted[] = $auditTrailEntity;
                }
            }
        }

        if (count($toBePersisted) > 0) {
            foreach ($toBePersisted as $auditTrailEntity){
                $this->em->persist($auditTrailEntity);
            }
            $this->em->getEventManager()->removeEventListener([Events::onFlush], $this);
            $this->em->flush();
            $this->em->getEventManager()->addEventListener([Events::onFlush], $this);
        }
    }
}
