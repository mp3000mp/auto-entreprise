<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new Post(normalizationContext: ['groups' => 'opportunity_show'], denormalizationContext: ['groups' => 'worked_time_add']),
        new Put(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'opportunity_show'], denormalizationContext: ['groups' => 'worked_time_edit']),
        new Delete(),
    ]
)]
class WorkedTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['opportunity_show'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['opportunity_show', 'worked_time_add', 'worked_time_edit'])]
    #[Assert\GreaterThan(0)]
    private float $workedDays;

    // todo opportunity ?
    #[ORM\ManyToOne(targetEntity: Opportunity::class, inversedBy: 'workedTimes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['worked_time_add'])]
    private Opportunity $opportunity;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'workedTimes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['worked_time_add'])]
    private User $user;

    #[ORM\Column(type: 'date')]
    #[Groups(['opportunity_show', 'worked_time_add', 'worked_time_edit'])]
    #[Assert\LessThanOrEqual('now')]
    private \DateTime $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkedDays(): float
    {
        return $this->workedDays;
    }

    public function setWorkedDays(float $workedDays): self
    {
        $this->workedDays = $workedDays;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
