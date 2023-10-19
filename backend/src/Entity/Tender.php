<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// unique constraint opportunity, version
#[ORM\Entity]
class Tender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TenderStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    private TenderStatus $status;

    #[ORM\ManyToOne(targetEntity: Opportunity::class, inversedBy: 'tenders')]
    #[ORM\JoinColumn(nullable: false)]
    private Opportunity $opportunity;

    #[ORM\Column]
    private int $version;

    #[ORM\Column]
    private int $averageDailyRate;

    #[ORM\Column]
    private \DateTime $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $acceptedAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $canceledAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $refusedAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $sentAt;

    /**
     * @var ArrayCollection<int, TenderRow>
     */
    #[ORM\OneToMany(targetEntity: TenderRow::class, mappedBy: 'tender')]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $tenderRows;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comments = null;

    /**
     * @var ArrayCollection<int, WorkedTime>
     */
    #[ORM\OneToMany(targetEntity: WorkedTime::class, mappedBy: 'tender')]
    private Collection $workedTimes;

    /**
     * @var ArrayCollection<int, TenderStatusLog>
     */
    #[ORM\OneToMany(targetEntity: TenderStatusLog::class, mappedBy: 'tender')]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $statusLogs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tenderFileDocx = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tenderFilePdf = null;

    public function __construct()
    {
        $this->tenderRows = new ArrayCollection();
        $this->workedTimes = new ArrayCollection();
        $this->statusLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): TenderStatus
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

    public function getSoldDays(): int
    {
        $soldDays = 0;
        foreach ($this->tenderRows as $tenderRow) {
            $soldDays += $tenderRow->getSoldDays();
        }

        return $soldDays;
    }

    public function getAmount(): int
    {
        $amount = 0;
        foreach ($this->tenderRows as $tenderRow) {
            $amount += ($tenderRow->getSoldDays() * $this->averageDailyRate);
        }

        return $amount;
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
     * @return ArrayCollection<int, WorkedTime>
     */
    public function getWorkedTimes(): Collection
    {
        return $this->workedTimes;
    }

    public function addWorkedTime(WorkedTime $workedTime): self
    {
        $this->workedTimes->add($workedTime);

        return $this;
    }

    public function removeWorkedTime(WorkedTime $workedTime): self
    {
        $this->workedTimes->removeElement($workedTime);

        return $this;
    }

    public function getTotalWorkedDays(): int
    {
        $n = 0;
        foreach ($this->workedTimes as $worked_time) {
            $n += $worked_time->getWorkedDays();
        }

        return $n;
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

    public function getTenderFileDocx(): ?string
    {
        return $this->tenderFileDocx;
    }

    public function setTenderFileDocx(?string $tenderFileDocx): self
    {
        $this->tenderFileDocx = $tenderFileDocx;

        return $this;
    }

    public function getTenderFilePdf(): ?string
    {
        return $this->tenderFilePdf;
    }

    public function setTenderFilePdf(?string $tenderFilePdf): self
    {
        $this->tenderFilePdf = $tenderFilePdf;

        return $this;
    }
}
