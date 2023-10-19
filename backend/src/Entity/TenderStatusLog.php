<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TenderStatusLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Tender::class, inversedBy: 'statusLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private Tender $tender;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\Column]
    private \DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: TenderStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    private TenderStatus $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTender(): Tender
    {
        return $this->tender;
    }

    public function setTender(Tender $tender): self
    {
        $this->tender = $tender;

        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $user): self
    {
        $this->createdBy = $user;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $date): self
    {
        $this->createdAt = $date;

        return $this;
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
}
