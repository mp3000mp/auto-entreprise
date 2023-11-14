<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'cost_list']),
        new Post(normalizationContext: ['groups' => 'cost_list'], denormalizationContext: ['groups' => 'cost_write']),
        new Put(normalizationContext: ['groups' => 'cost_list'], denormalizationContext: ['groups' => 'cost_write']),
        new Delete(),
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
    #[Groups(['cost_list', 'cost_write'])]
    private \DateTime $date;

    #[ORM\Column(length: 255)]
    #[Groups(['cost_list', 'cost_write'])]
    #[Assert\NotBlank]
    private string $description;

    #[ORM\Column]
    #[Groups(['cost_list', 'cost_write'])]
    #[Assert\GreaterThan(0)]
    private float $amount;

    #[ORM\ManyToOne(targetEntity: CostType::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cost_list', 'cost_write'])]
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
