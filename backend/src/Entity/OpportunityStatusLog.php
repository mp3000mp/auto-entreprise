<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class OpportunityStatusLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Opportunity::class, inversedBy: 'statusLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private Opportunity $opportunity;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserInterface $createdBy;

    #[ORM\Column]
    #[Groups(['opportunity_show'])]
    private \DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: OpportunityStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['opportunity_show'])]
    private OpportunityStatus $status;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserInterface $user): self
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
