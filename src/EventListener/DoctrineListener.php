<?php
	
	namespace App\EventListener;
	
	use App\Entity\Opportunity;
	use App\Entity\OpportunityStatus;
	use App\Entity\OpportunityStatusLog;
	use App\Entity\Tender;
	use App\Entity\TenderStatusLog;
	use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
	use Doctrine\ORM\Event\OnFlushEventArgs;
	use Doctrine\ORM\Events;
	use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
	
	class DoctrineListener
	{
		
		/**
		 * @var TokenStorageInterface
		 */
		private $tokenStorage;
		
		public function __construct(TokenStorageInterface $tokenStorage)
		{
			$this->tokenStorage = $tokenStorage;
		}
		
		public function onFlush(OnFlushEventArgs $args )
		{
			
			$em  = $args->getEntityManager();
			$uow = $em->getUnitOfWork();
			
			foreach ($uow->getScheduledEntityUpdates() as $entity) {
				// opportunity: on set date en fonction du statut
				if ($entity instanceof Opportunity) {
					$changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($entity);
					if(isset($changeSet['status'])){
						switch ($changeSet['status'][1]->getId()){
							case Opportunity::STATUS_DEVELOP_ONGOING:
								$entity->setPurchasedAt( new \DateTime() );
								break;
							case Opportunity::STATUS_DELIVERED:
								$entity->setDeliveredAt(new \DateTime());
								break;
							case Opportunity::STATUS_BILLED:
								$entity->setBilledAt(new \DateTime());
								break;
							case Opportunity::STATUS_PAYED:
								$entity->setPayedAt(new \DateTime());
								break;
							case Opportunity::STATUS_CANCELED:
								$entity->setCanceledAt(new \DateTime());
								break;
						}
						
						// log
						$log = new OpportunityStatusLog();
						$log->setOpportunity($entity);
						$log->setCreatedBy($this->tokenStorage->getToken()->getUser());
						$log->setCreatedAt(new \DateTime());
						$log->setStatus($entity->getStatus());
						$entity->addStatusLog($log);
						
						// recompute
						$em->persist($log);
						$metaData = $em->getClassMetadata(get_class($entity));
						$uow->recomputeSingleEntityChangeSet($metaData, $entity);
						$uow->computeChangeSets();
						
					}
				}
				
				// tender, on set date en fonction du statut
				if ($entity instanceof Tender) {
					$changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($entity);
					if(isset($changeSet['status'])){
						switch ($changeSet['status'][1]->getId()) {
							case Tender::STATUS_SENT:
								$entity->setSentAt( new \DateTime() );
								
								// change opportunity status
								$opportunity = $entity->getOpportunity();
								$statusRepo = $args->getEntityManager()->getRepository(OpportunityStatus::class);
								$status = $statusRepo->find(Opportunity::STATUS_TENDER_ONGOING);
								$opportunity->setStatus($status);
								
								// log
								$log = new OpportunityStatusLog();
								$log->setOpportunity($opportunity);
								$log->setCreatedBy($this->tokenStorage->getToken()->getUser());
								$log->setCreatedAt(new \DateTime());
								$log->setStatus($opportunity->getStatus());
								$opportunity->addStatusLog($log);
								$em->persist($log);

								break;
							case Tender::STATUS_ACCEPTED:
								$entity->setAcceptedAt( new \DateTime() );
								
								// change opportunity status
								$opportunity = $entity->getOpportunity();
								$statusRepo = $args->getEntityManager()->getRepository(OpportunityStatus::class);
								$status = $statusRepo->find(Opportunity::STATUS_DEVELOP_ONGOING);
								$opportunity->setStatus($status);
								$opportunity->setPurchasedAt(new \DateTime());
								
								// log
								$log = new OpportunityStatusLog();
								$log->setOpportunity($opportunity);
								$log->setCreatedBy($this->tokenStorage->getToken()->getUser());
								$log->setCreatedAt(new \DateTime());
								$log->setStatus($opportunity->getStatus());
								$opportunity->addStatusLog($log);
								$em->persist($log);
								
								break;
							case Tender::STATUS_REFUSED:
								$entity->setRefusedAt( new \DateTime() );
								break;
							case Tender::STATUS_CANCELED:
								$entity->setCanceledAt( new \DateTime() );
								break;
						}
						
						// log
						$log = new TenderStatusLog();
						$log->setTender($entity);
						$log->setCreatedBy($this->tokenStorage->getToken()->getUser());
						$log->setCreatedAt(new \DateTime());
						$log->setStatus($entity->getStatus());
						$entity->addStatusLog($log);
						
						// recompute
						$em->persist($log);
						$metaData = $em->getClassMetadata(get_class($entity));
						$uow->recomputeSingleEntityChangeSet($metaData, $entity);
						$uow->computeChangeSets();
						
					}
				}
			}
		}
	}
