<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class WorkedTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private float $workedDays;

    // todo opportunity ?
    #[ORM\ManyToOne(targetEntity: Tender::class, inversedBy: 'workedTimes')]
    #[ORM\JoinColumn(nullable: false)]
    private Tender $tender;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'workedTimes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[Assert\LessThanOrEqual('now')]
    #[ORM\Column]
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

    public function getTender(): Tender
    {
        return $this->tender;
    }

    public function setTender(Tender $tender): self
    {
        $this->tender = $tender;

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
