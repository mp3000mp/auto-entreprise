<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TenderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(TenderRepository::class)]
#[ORM\UniqueConstraint(columns: ['version', 'opportunity_id'])]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'tender_list']),
        new Get(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'tender_show']),
        new Post(normalizationContext: ['groups' => 'tender_show'], denormalizationContext: ['groups' => 'tender_add']),
        new Put(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'tender_show'], denormalizationContext: ['groups' => 'tender_edit']),
    ]
)]
class Tender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tender_show', 'tender_list', 'opportunity_list', 'opportunity_show', 'company_show', 'contact_show'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TenderStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tender_show', 'tender_list', 'opportunity_list', 'opportunity_show', 'company_show', 'contact_show', 'tender_add', 'tender_edit'])]
    private TenderStatus $status;

    #[ORM\ManyToOne(targetEntity: Opportunity::class, inversedBy: 'tenders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tender_show', 'tender_list', 'tender_add'])]
    private Opportunity $opportunity;

    #[ORM\Column]
    #[Groups(['tender_show', 'tender_list', 'opportunity_show', 'tender_add', 'tender_edit'])]
    #[Assert\NotBlank]
    private int $version;

    #[ORM\Column]
    #[Groups(['tender_show', 'tender_list', 'opportunity_list', 'opportunity_show', 'tender_add', 'tender_edit'])]
    private int $averageDailyRate;

    #[ORM\Column]
    #[Groups(['tender_show', 'tender_list'])]
    private \DateTime $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['tender_show', 'tender_add', 'tender_edit'])]
    private ?\DateTime $acceptedAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['tender_show', 'tender_edit'])]
    private ?\DateTime $canceledAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['tender_show', 'tender_add', 'tender_edit'])]
    private ?\DateTime $refusedAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['tender_show', 'tender_add', 'tender_edit'])]
    private ?\DateTime $sentAt;

    /**
     * @var ArrayCollection<int, TenderRow>
     */
    #[ORM\OneToMany(targetEntity: TenderRow::class, mappedBy: 'tender')]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Groups(['tender_show'])]
    private Collection $tenderRows;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['tender_show', 'tender_add', 'tender_edit'])]
    private ?string $comments = null;

    /**
     * @var ArrayCollection<int, TenderStatusLog>
     */
    #[ORM\OneToMany(targetEntity: TenderStatusLog::class, mappedBy: 'tender')]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    #[Groups(['tender_show'])]
    private Collection $statusLogs;

    /**
     * @var ArrayCollection<int, TenderFile>
     */
    #[ORM\OneToMany(targetEntity: TenderFile::class, mappedBy: 'tender')]
    #[Groups(['tender_show'])]
    private Collection $tenderFiles;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->tenderRows = new ArrayCollection();
        $this->statusLogs = new ArrayCollection();
        $this->tenderFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?TenderStatus
    {
        return $this->status;
    }

    public function setStatus(TenderStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOpportunity(): Opportunity
    {
        return $this->opportunity;
    }

    public function setOpportunity(Opportunity $opportunity): self
    {
        $this->opportunity = $opportunity;

        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getAverageDailyRate(): int
    {
        return $this->averageDailyRate;
    }

    public function setAverageDailyRate(int $averageDailyRate): self
    {
        $this->averageDailyRate = $averageDailyRate;

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

    public function getAcceptedAt(): ?\DateTime
    {
        return $this->acceptedAt;
    }

    public function setAcceptedAt(?\DateTime $acceptedAt): self
    {
        $this->acceptedAt = $acceptedAt;

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

    public function getRefusedAt(): ?\DateTime
    {
        return $this->refusedAt;
    }

    public function setRefusedAt(?\DateTime $refusedAt): self
    {
        $this->refusedAt = $refusedAt;

        return $this;
    }

    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTime $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * @return ArrayCollection<int, TenderRow>
     */
    public function getTenderRows(): Collection
    {
        return $this->tenderRows;
    }

    public function addTenderRow(TenderRow $tenderRow): self
    {
        $this->tenderRows->add($tenderRow);

        return $this;
    }

    public function removeTenderRow(TenderRow $tenderRow): self
    {
        $this->tenderRows->removeElement($tenderRow);

        return $this;
    }

    #[Groups(['tender_show', 'tender_list', 'opportunity_list', 'opportunity_show', 'company_show', 'contact_show'])]
    public function getSoldDays(): float
    {
        $soldDays = 0;
        foreach ($this->tenderRows as $tenderRow) {
            $soldDays += $tenderRow->getSoldDays();
        }

        return round($soldDays, 2);
    }

    #[Groups(['tender_show', 'tender_list', 'opportunity_list', 'opportunity_show', 'company_show', 'contact_show'])]
    public function getTotalRate(): float
    {
        $total = 0;
        foreach ($this->tenderRows as $tenderRow) {
            $total += $tenderRow->getFixedRate() + $tenderRow->getSoldDays()*$this->getAverageDailyRate();
        }

        return $total;
    }

    public function getAmount(): float
    {
        return $this->getSoldDays() * $this->averageDailyRate;
    }

    public function getNextPosition(): int
    {
        return count($this->getTenderRows()) + 1;
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
     * @return ArrayCollection<int, TenderStatusLog>
     */
    public function getStatusLogs(): Collection
    {
        return $this->statusLogs;
    }

    public function addStatusLog(TenderStatusLog $statusLog): self
    {
        $this->statusLogs->add($statusLog);

        return $this;
    }

    public function removeStatusLog(TenderStatusLog $statusLog): self
    {
        $this->statusLogs->removeElement($statusLog);

        return $this;
    }

    /**
     * @return ArrayCollection<int, TenderFile>
     */
    public function getTenderFiles(): Collection
    {
        return $this->tenderFiles;
    }

    public function addTenderFile(TenderFile $tenderFile): self
    {
        $this->tenderFiles->add($tenderFile);

        return $this;
    }

    public function removeTenderFile(TenderFile $tenderFile): self
    {
        $this->tenderFiles->removeElement($tenderFile);

        return $this;
    }
}
