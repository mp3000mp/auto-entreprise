<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Cost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTime $date;

    #[ORM\Column(length: 255)]
    private string $description;

    #[ORM\Column]
    private float $amount;

    #[ORM\ManyToOne(targetEntity: CostType::class)]
    #[ORM\JoinColumn]
    private CostType $type;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): CostType
    {
        return $this->type;
    }

    public function setType(CostType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
