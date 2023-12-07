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
#[ORM\UniqueConstraint(columns: ['position', 'tender_id'])]
#[ApiResource(
    operations: [
        new Post(normalizationContext: ['groups' => 'tender_show'], denormalizationContext: ['groups' => 'tender_row_add']),
        new Put(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'tender_show'], denormalizationContext: ['groups' => 'tender_row_edit']),
        new Delete(),
    ]
)]
class TenderRow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tender_show'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['tender_show', 'tender_row_add', 'tender_row_edit'])]
    #[Assert\GreaterThan(0)]
    private int $position;

    #[ORM\Column]
    #[Groups(['tender_show', 'tender_row_add', 'tender_row_edit'])]
    #[Assert\GreaterThanOrEqual(0)]
    private float $soldDays;

    #[ORM\Column(length: 255)]
    #[Groups(['tender_show', 'tender_row_add', 'tender_row_edit'])]
    #[Assert\NotBlank]
    private string $title;

    #[ORM\Column(length: 255)]
    #[Groups(['tender_show', 'tender_row_add', 'tender_row_edit'])]
    #[Assert\NotBlank]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Tender::class, inversedBy: 'tenderRows')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tender_row_add'])]
    private Tender $tender;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSoldDays(): float
    {
        return $this->soldDays;
    }

    public function setSoldDays(float $soldDays): self
    {
        $this->soldDays = $soldDays;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getTender(): Tender
    {
        return $this->tender;
    }

    public function setTender(Tender $tender): self
    {
        $this->tender = $tender;

        return $this;
    }
}
