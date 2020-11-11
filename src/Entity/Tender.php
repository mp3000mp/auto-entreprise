<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TenderRepository")
 */
class Tender
{
    public const STATUS_SENT = 2;
    public const STATUS_ACCEPTED = 3;
    public const STATUS_REFUSED = 4;
    public const STATUS_CANCELED = 5;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TenderStatus")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var TenderStatus
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Opportunity", inversedBy="tenders")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Opportunity
     */
    private $opportunity;

    /**
     * @ORM\Column(type="smallint")
     *
     * @var int
     */
    private $version;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $averageDailyRate;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    private $acceptedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    private $canceledAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    private $refusedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    private $sentAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TenderRow", mappedBy="tender")
     *
     * @var ArrayCollection<int, TenderRow>
     */
    private $tenderRows;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string|null
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Workedtime", mappedBy="tender")
     *
     * @var ArrayCollection<int, WorkedTime>
     */
    private $workedTimes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TenderStatusLog", mappedBy="tender")
     * @ORM\OrderBy({"createdAt" = "ASC"})
     *
     * @var ArrayCollection<int, TenderStatusLog>
     */
    private $statusLogs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $tenderFileDocx;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $tenderFilePdf;

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

    public function getStatus(): ?TenderStatus
    {
        return $this->status;
    }

    public function setStatus(?TenderStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOpportunity(): ?Opportunity
    {
        return $this->opportunity;
    }

    public function setOpportunity(?Opportunity $opportunity): self
    {
        $this->opportunity = $opportunity;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getAverageDailyRate(): ?int
    {
        return $this->averageDailyRate;
    }

    public function setAverageDailyRate(int $averageDailyRate): self
    {
        $this->averageDailyRate = $averageDailyRate;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAcceptedAt(): ?DateTime
    {
        return $this->acceptedAt;
    }

    public function setAcceptedAt(?DateTime $acceptedAt): self
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }

    public function getCanceledAt(): ?DateTime
    {
        return $this->canceledAt;
    }

    public function setCanceledAt(?DateTime $canceledAt): self
    {
        $this->canceledAt = $canceledAt;

        return $this;
    }

    public function getRefusedAt(): ?DateTime
    {
        return $this->refusedAt;
    }

    public function setRefusedAt(?DateTime $refusedAt): self
    {
        $this->refusedAt = $refusedAt;

        return $this;
    }

    public function getSentAt(): ?DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(?DateTime $sentAt): self
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
        if (!$this->tenderRows->contains($tenderRow)) {
            $this->tenderRows[] = $tenderRow;
            $tenderRow->setTender($this);
        }

        return $this;
    }

    public function removeTenderRow(TenderRow $tenderRow): self
    {
        if ($this->tenderRows->contains($tenderRow)) {
            $this->tenderRows->removeElement($tenderRow);
            // set the owning side to null (unless already changed)
            if ($tenderRow->getTender() === $this) {
                $tenderRow->setTender(null);
            }
        }

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
        if (!$this->workedTimes->contains($workedTime)) {
            $this->workedTimes[] = $workedTime;
            $workedTime->setTender($this);
        }

        return $this;
    }

    public function removeWorkedTime(WorkedTime $workedTime): self
    {
        if ($this->workedTimes->contains($workedTime)) {
            $this->workedTimes->removeElement($workedTime);
        }

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
        if (!$this->statusLogs->contains($statusLog)) {
            $this->statusLogs[] = $statusLog;
            $statusLog->setTender($this);
        }

        return $this;
    }

    public function removeStatusLog(TenderStatusLog $statusLog): self
    {
        if ($this->statusLogs->contains($statusLog)) {
            $this->statusLogs->removeElement($statusLog);
            // set the owning side to null (unless already changed)
            if ($statusLog->getTender() === $this) {
                $statusLog->setTender(null);
            }
        }

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

    public function jsonize(): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'averageDailyRate' => $this->averageDailyRate,
            'status' => $this->status->getTrad(),
            'totalWorkedDays' => $this->getTotalWorkedDays(),
        ];
    }
}
