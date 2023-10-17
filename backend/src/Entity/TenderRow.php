<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

// todo unique constraint tender, position
#[ORM\Entity]
class TenderRow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $position;

    #[ORM\Column]
    private float $soldDays;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Tender::class, inversedBy: 'tenderRows')]
    #[ORM\JoinColumn]
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
