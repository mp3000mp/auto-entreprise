<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TenderRowRepository")
 */
class TenderRow
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
     * @ORM\Column(type="smallint")
     *
     * @var int
     */
    private $position;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    private $soldDays;

    /**
     * @ORM\Column(type="string", length=55)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tender", inversedBy="tenderRows")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Tender
     */
    private $tender;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSoldDays(): ?float
    {
        return $this->soldDays;
    }

    public function setSoldDays(float $soldDays): self
    {
        $this->soldDays = $soldDays;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getTender(): ?Tender
    {
        return $this->tender;
    }

    public function setTender(?Tender $tender): self
    {
        $this->tender = $tender;

        return $this;
    }
}
