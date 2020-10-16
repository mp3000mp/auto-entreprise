<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OpportunityRepository")
 */
class Opportunity
{
	
	
	const STATUS_TRACKED = 1;
	const STATUS_TENDER_ONGOING = 2;
	const STATUS_DEVELOP_ONGOING = 3;
	const STATUS_DELIVERED = 4; // recette
	const STATUS_BILLED = 5;
	const STATUS_PAYED = 6;
	const STATUS_CANCELED = 7;
	const STATUS_TENDER_SENT = 8;
	const STATUS_NEED_ONGOING = 9;
	const STATUS_NEED_SENT = 10;
	
	
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="opportunities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $canceledAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $billedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $payedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $purchasedAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $trackedAt;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $deliveredAt;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $forecastedDelivery;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $customerRef1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $customerRef2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $paymentRef;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contact", inversedBy="opportunities")
     */
    private $contacts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OpportunityStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MeanOfPayment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meanOfPayment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tender", mappedBy="opportunity")
     */
    private $tenders;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;
	
	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\OpportunityStatusLog", mappedBy="opportunity")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
	 */
	private $statusLogs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billFileDocx;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billFilePdf;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OpportunityFile", mappedBy="opportunity")
     */
    private $opportunityFiles;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->tenders = new ArrayCollection();
        $this->opportunityFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCanceledAt(): ?\DateTimeInterface
    {
        return $this->canceledAt;
    }

    public function setCanceledAt(?\DateTimeInterface $canceledAt): self
    {
        $this->canceledAt = $canceledAt;

        return $this;
    }
	
	/**
	 * @return \DateTimeInterface
	 */
	public function getDeliveredAt() {
                                 		return $this->deliveredAt;
                                 	}
	
	/**
	 * @param \DateTimeInterface|null $deliveredAt
	 *
	 * @return $this
	 */
	public function setDeliveredAt(?\DateTimeInterface $deliveredAt ): self {
                                 		$this->deliveredAt = $deliveredAt;
                                 		return $this;
                                 	}
	
	/**
	 * @return \DateTimeInterface
	 */
	public function getForecastedDelivery() {
                                 		return $this->forecastedDelivery;
                                 	}
	
	/**
	 * @param \DateTimeInterface|null $forecastedDelivery
	 *
	 * @return $this
	 */
	public function setForecastedDelivery(?\DateTimeInterface $forecastedDelivery ): self {
                                 		$this->forecastedDelivery = $forecastedDelivery;
                                 		return $this;
                                 	}
	
	/**
	 * @return \DateTimeInterface|null
	 */
    public function getBilledAt(): ?\DateTimeInterface
    {
        return $this->billedAt;
    }
	
	/**
	 * @param \DateTimeInterface|null $billedAt
	 *
	 * @return Opportunity
	 */
    public function setBilledAt(?\DateTimeInterface $billedAt): self
    {
        $this->billedAt = $billedAt;

        return $this;
    }

    public function getPayedAt(): ?\DateTimeInterface
    {
        return $this->payedAt;
    }

    public function setPayedAt(?\DateTimeInterface $payedAt): self
    {
        $this->payedAt = $payedAt;

        return $this;
    }

    public function getPurchasedAt(): ?\DateTimeInterface
    {
        return $this->purchasedAt;
    }

    public function setPurchasedAt(?\DateTimeInterface $purchasedAt): self
    {
        $this->purchasedAt = $purchasedAt;

        return $this;
    }

    public function getTrackedAt(): ?\DateTimeInterface
    {
        return $this->trackedAt;
    }

    public function setTrackedAt(?\DateTimeInterface $trackedAt): self
    {
        $this->trackedAt = $trackedAt;

        return $this;
    }

    public function getCustomerRef1(): ?string
    {
        return $this->customerRef1;
    }

    public function setCustomerRef1(?string $customerRef1): self
    {
        $this->customerRef1 = $customerRef1;

        return $this;
    }

    public function getCustomerRef2(): ?string
    {
        return $this->customerRef2;
    }

    public function setCustomerRef2(?string $customerRef2): self
    {
        $this->customerRef2 = $customerRef2;

        return $this;
    }

    public function getPaymentRef(): ?string
    {
        return $this->paymentRef;
    }

    public function setPaymentRef(?string $paymentRef): self
    {
        $this->paymentRef = $paymentRef;

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    public function getStatus(): ?OpportunityStatus
    {
        return $this->status;
    }

    public function setStatus(?OpportunityStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMeanOfPayment(): ?MeanOfPayment
    {
        return $this->meanOfPayment;
    }

    public function setMeanOfPayment(?MeanOfPayment $meanOfPayment): self
    {
        $this->meanOfPayment = $meanOfPayment;

        return $this;
    }

    /**
     * @return Collection|Tender[]
     */
    public function getTenders(): Collection
    {
        return $this->tenders;
    }

    public function addTender(Tender $tender): self
    {
        if (!$this->tenders->contains($tender)) {
            $this->tenders[] = $tender;
            $tender->setOpportunity($this);
        }

        return $this;
    }

    public function removeTender(Tender $tender): self
    {
        if ($this->tenders->contains($tender)) {
            $this->tenders->removeElement($tender);
            // set the owning side to null (unless already changed)
            if ($tender->getOpportunity() === $this) {
                $tender->setOpportunity(null);
            }
        }

        return $this;
    }
    
    public function getNextVersion(){
    	return count($this->getTenders())+1;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
	
	
	/**
	 * @return Collection|OpportunityStatusLog[]
	 */
	public function getStatusLogs(): Collection
                                 	{
                                 		return $this->statusLogs;
                                 	}
	
	public function addStatusLog(OpportunityStatusLog $statusLog): self
                                 	{
                                 		if (!$this->statusLogs->contains($statusLog)) {
                                 			$this->statusLogs[] = $statusLog;
                                 			$statusLog->setOpportunity($this);
                                 		}
                                 		
                                 		return $this;
                                 	}
	
	public function removeStatusLog(OpportunityStatusLog $statusLog): self
                                 	{
                                 		if ($this->statusLogs->contains($statusLog)) {
                                 			$this->statusLogs->removeElement($statusLog);
                                 			// set the owning side to null (unless already changed)
                                 			if ($statusLog->getOpportunity() === $this) {
                                 				$statusLog->setOpportunity(null);
                                 			}
                                 		}
                                 		
                                 		return $this;
                                 	}

    public function getBillFileDocx(): ?string
    {
        return $this->billFileDocx;
    }

    public function setBillFileDocx(?string $billFileDocx): self
    {
        $this->billFileDocx = $billFileDocx;

        return $this;
    }

    public function getBillFilePdf(): ?string
    {
        return $this->billFilePdf;
    }

    public function setBillFilePdf(?string $billFilePdf): self
    {
        $this->billFilePdf = $billFilePdf;

        return $this;
    }

    /**
     * @return Collection|OpportunityFile[]
     */
    public function getOpportunityFiles(): Collection
    {
        return $this->opportunityFiles;
    }

    public function addOpportunityFile(OpportunityFile $opportunityFile): self
    {
        if (!$this->opportunityFiles->contains($opportunityFile)) {
            $this->opportunityFiles[] = $opportunityFile;
            $opportunityFile->setOpportunity($this);
        }

        return $this;
    }

    public function removeOpportunityFile(OpportunityFile $opportunityFile): self
    {
        if ($this->opportunityFiles->contains($opportunityFile)) {
            $this->opportunityFiles->removeElement($opportunityFile);
            // set the owning side to null (unless already changed)
            if ($opportunityFile->getOpportunity() === $this) {
                $opportunityFile->setOpportunity(null);
            }
        }

        return $this;
    }
}
