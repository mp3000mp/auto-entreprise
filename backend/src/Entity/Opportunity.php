<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\OpportunityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OpportunityRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'opportunity_list']),
        new Get(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'opportunity_show']),
        new Post(normalizationContext: ['groups' => 'opportunity_show'], denormalizationContext: ['groups' => 'opportunity_add']),
        new Put(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'opportunity_show'], denormalizationContext: ['groups' => 'opportunity_edit']),
    ]
)]
class Opportunity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['opportunity_list', 'opportunity_show', 'company_show', 'contact_show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['opportunity_list', 'opportunity_show', 'company_show', 'contact_show', 'opportunity_add', 'opportunity_edit'])]
    #[Assert\NotBlank]
    private string $ref;

    #[ORM\Column(length: 255)]
    #[Groups(['opportunity_list', 'opportunity_show', 'company_show', 'contact_show', 'opportunity_add', 'opportunity_edit'])]
    #[Assert\NotBlank]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'opportunities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['opportunity_list', 'opportunity_show', 'opportunity_add'])]
    private Company $company;

    #[ORM\Column]
    private \DateTime $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?\DateTime $canceledAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?\DateTime $billedAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?\DateTime $payedAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?\DateTime $purchasedAt;

    #[ORM\Column]
    #[Groups(['opportunity_show', 'opportunity_add', 'opportunity_edit'])]
    private \DateTime $trackedAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?\DateTime $deliveredAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['opportunity_list', 'opportunity_show', 'opportunity_add', 'opportunity_edit'])]
    private ?\DateTime $forecastedDelivery;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_add', 'opportunity_edit'])]
    private ?string $customerRef1 = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_add', 'opportunity_edit'])]
    private ?string $customerRef2 = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_add', 'opportunity_edit'])]
    private ?string $paymentRef = null;

    /**
     * @var ArrayCollection<int, Contact>
     */
    #[ORM\ManyToMany(targetEntity: Contact::class, inversedBy: 'opportunities')]
    #[ORM\JoinTable('opportunity_contact')]
    #[Groups(['opportunity_show', 'opportunity_add', 'opportunity_edit'])]
    private Collection $contacts;

    #[ORM\ManyToOne(targetEntity: OpportunityStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['opportunity_list', 'opportunity_show', 'company_show', 'contact_show', 'opportunity_edit'])]
    private ?OpportunityStatus $status = null;

    #[ORM\ManyToOne(targetEntity: MeanOfPayment::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?MeanOfPayment $meanOfPayment;

    /**
     * @var ArrayCollection<int, Tender>
     */
    #[ORM\OneToMany(targetEntity: Tender::class, mappedBy: 'opportunity')]
    #[ORM\OrderBy(['version' => 'DESC'])]
    #[Groups(['opportunity_show'])]
    private Collection $tenders;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?string $comments = null;

    /**
     * @var ArrayCollection<int, OpportunityStatusLog>
     */
    #[ORM\OneToMany(targetEntity: OpportunityStatusLog::class, mappedBy: 'opportunity')]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    #[Groups(['opportunity_show'])]
    private Collection $statusLogs;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?string $billFileDocx = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['opportunity_show', 'opportunity_edit'])]
    private ?string $billFilePdf = null;

    /**
     * @var ArrayCollection<int, OpportunityFile>
     */
    #[ORM\OneToMany(targetEntity: OpportunityFile::class, mappedBy: 'opportunity')]
    #[Groups(['opportunity_show'])]
    private Collection $opportunityFiles;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->contacts = new ArrayCollection();
        $this->tenders = new ArrayCollection();
        $this->opportunityFiles = new ArrayCollection();
        $this->statusLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?OpportunityStatus
    {
        return $this->status;
    }

    public function setStatus(OpportunityStatus $status): self
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

    #[Groups(['opportunity_list', 'company_show', 'contact_show'])]
    public function getLastTender(): ?Tender
    {
        $lastTender = null;
        foreach ($this->tenders as $tender) {
            if (null === $lastTender || $lastTender->getVersion() < $tender->getVersion()) {
                $lastTender = $tender;
            }
        }

        return $lastTender;
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
