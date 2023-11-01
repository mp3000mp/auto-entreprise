<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'cost_list']),
    ]
)]
class Cost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cost_list'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['cost_list'])]
    private \DateTime $date;

    #[ORM\Column(length: 255)]
    #[Groups(['cost_list'])]
    private string $description;

    #[ORM\Column]
    #[Groups(['cost_list'])]
    private float $amount;

    #[ORM\ManyToOne(targetEntity: CostType::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cost_list'])]
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
