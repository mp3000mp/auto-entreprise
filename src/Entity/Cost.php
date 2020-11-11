<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CostRepository")
 */
class Cost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CostType", inversedBy="costs")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var CostType|null
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): ?CostType
    {
        return $this->type;
    }

    public function setType(?CostType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function jsonize(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'amount' => $this->amount,
            'description' => $this->description,
            'type' => $this->type->jsonize(),
        ];
    }
}
