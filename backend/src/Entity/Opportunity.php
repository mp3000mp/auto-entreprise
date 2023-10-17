<?php

namespace App\Entity;

use App\Repository\OpportunityRepository;
use App\Service\AuditTrail\AuditrailableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpportunityRepository::class)]
class Opportunity implements AuditrailableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $ref;

    #[ORM\Column(length: 255)]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'opportunities')]
    #[ORM\JoinColumn]
    private Company $company;

    #[ORM\Column]
    private \DateTime $createdAt;

    #[ORM\Column]
    private ?\DateTime $canceledAt;

    #[ORM\Column]
    private ?\DateTime $billedAt;

    #[ORM\Column]
    private ?\DateTime $payedAt;

    #[ORM\Column]
    private ?\DateTime $purchasedAt;

    #[ORM\Column]
    private \DateTime $trackedAt;

    #[ORM\Column]
    private ?\DateTime $deliveredAt;

    #[ORM\Column]
    private ?\DateTime $forecastedDelivery;

    #[ORM\Column(length: 100)]
    private ?string $customerRef1 = null;

    #[ORM\Column(length: 100)]
    private ?string $customerRef2 = null;

    #[ORM\Column(length: 100)]
    private ?string $paymentRef = null;

    /**
     * @var ArrayCollection<int, Contact>
     */
    #[ORM\ManyToMany(targetEntity: Contact::class, inversedBy: 'opportunities')]
    private Collection $contacts;

    #[ORM\ManyToOne(targetEntity: OpportunityStatus::class)]
    #[ORM\JoinColumn]
    private OpportunityStatus $status;

    #[ORM\ManyToOne(targetEntity: MeanOfPayment::class)]
    #[ORM\JoinColumn]
    private MeanOfPayment $meanOfPayment;

    /**
     * @var ArrayCollection<int, Tender>
     */
    #[ORM\OneToMany(targetEntity: Tender::class, mappedBy: 'opportunity')]
    #[ORM\OrderBy(['version' => 'DESC'])]
    private Collection $tenders;

    #[ORM\Column(type: 'text')]
    private ?string $comments = null;

    /**
     * @var ArrayCollection<int, OpportunityStatusLog>
     */
    #[ORM\OneToMany(targetEntity: OpportunityStatusLog::class, mappedBy: 'opportunity')]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $statusLogs;

    #[ORM\Column(length: 255)]
    private ?string $billFileDocx = null;

    #[ORM\Column(length: 255)]
    private ?string $billFilePdf = null;

    /**
     * @var ArrayCollection<int, OpportunityFile>
     */
    #[ORM\OneToMany(targetEntity: OpportunityFile::class, mappedBy: 'opportunity')]
    private Collection $opportunityFiles;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->tenders = new ArrayCollection();
        $this->opportunityFiles = new ArrayCollection();
        $this->statusLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuditTrailString(): string
    {
        return $this->getRef();
    }

    public function getFieldsToBeIgnored(): array
    {
        return [];
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCanceledAt(): ?\DateTime
    {
        return $this->canceledAt;
    }

    public function setCanceledAt(?\DateTime $canceledAt): self
    {
        $this->canceledAt = $canceledAt;

        return $this;
    }

    public function getDeliveredAt(): ?\DateTime
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(?\DateTime $deliveredAt): self
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }

    public function getForecastedDelivery(): ?\DateTime
    {
        return $this->forecastedDelivery;
    }

    public function setForecastedDelivery(?\DateTime $forecastedDelivery): self
    {
        $this->forecastedDelivery = $forecastedDelivery;

        return $this;
    }

    public function getBilledAt(): ?\DateTime
    {
        return $this->billedAt;
    }

    public function setBilledAt(?\DateTime $billedAt): self
    {
        $this->billedAt = $billedAt;

        return $this;
    }

    public function getPayedAt(): ?\DateTime
    {
        return $this->payedAt;
    }

    public function setPayedAt(?\DateTime $payedAt): self
    {
        $this->payedAt = $payedAt;

        return $this;
    }

    public function getPurchasedAt(): ?\DateTime
    {
        return $this->purchasedAt;
    }

    public function setPurchasedAt(?\DateTime $purchasedAt): self
    {
        $this->purchasedAt = $purchasedAt;

        return $this;
    }

    public function getTrackedAt(): \DateTime
    {
        return $this->trackedAt;
    }

    public function setTrackedAt(\DateTime $trackedAt): self
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
     * @return ArrayCollection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        $this->contacts->add($contact);

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        $this->contacts->removeElement($contact);

        return $this;
    }

    public function getStatus(): OpportunityStatus
    {
        return $this->status;
    }

    public function setStatus(OpportunityStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMeanOfPayment(): MeanOfPayment
    {
        return $this->meanOfPayment;
    }

    public function setMeanOfPayment(MeanOfPayment $meanOfPayment): self
    {
        $this->meanOfPayment = $meanOfPayment;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Tender>
     */
    public function getTenders(): Collection
    {
        return $this->tenders;
    }

    public function addTender(Tender $tender): self
    {
        $this->tenders->add($tender);

        return $this;
    }

    public function removeTender(Tender $tender): self
    {
        $this->tenders->removeElement($tender);

        return $this;
    }

    public function getNextVersion(): int
    {
        return count($this->getTenders()) + 1;
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
     * @return ArrayCollection<int, OpportunityStatusLog>
     */
    public function getStatusLogs(): Collection
    {
        return $this->statusLogs;
    }

    public function addStatusLog(OpportunityStatusLog $statusLog): self
    {
        $this->statusLogs->add($statusLog);

        return $this;
    }

    public function removeStatusLog(OpportunityStatusLog $statusLog): self
    {
        $this->statusLogs->removeElement($statusLog);

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
     * @return ArrayCollection<int, OpportunityFile>
     */
    public function getOpportunityFiles(): Collection
    {
        return $this->opportunityFiles;
    }

    public function addOpportunityFile(OpportunityFile $opportunityFile): self
    {
        $this->opportunityFiles->add($opportunityFile);

        return $this;
    }

    public function removeOpportunityFile(OpportunityFile $opportunityFile): self
    {
        $this->opportunityFiles->removeElement($opportunityFile);

        return $this;
    }
}
