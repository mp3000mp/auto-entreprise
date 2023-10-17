<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class OpportunityStatusLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Opportunity::class, inversedBy: 'statusLogs')]
    #[ORM\JoinColumn]
    private Opportunity $Opportunity;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn]
    private User $createdBy;

    #[ORM\Column]
    private \DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: OpportunityStatus::class)]
    #[ORM\JoinColumn]
    private OpportunityStatus $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpportunity(): Opportunity
    {
        return $this->Opportunity;
    }

    public function setOpportunity(Opportunity $Opportunity): self
    {
        $this->Opportunity = $Opportunity;

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

    public function getStatus(): OpportunityStatus
    {
        return $this->status;
    }

    public function setStatus(OpportunityStatus $status): self
    {
        $this->status = $status;

        return $this;
    }
}
