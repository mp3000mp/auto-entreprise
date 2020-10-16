<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OpportunityStatusRepository")
 */
class OpportunityStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $trad = [];

    /**
     * @ORM\Column(type="smallint")
     */
    private $position;

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

    public function getTrad(): array
    {
        return $this->trad;
    }

    public function setTrad($trad): self
    {
        $this->trad = $trad;

        return $this;
    }
}
