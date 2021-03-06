<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TenderStatusRepository")
 */
class TenderStatus
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
     * @ORM\Column(type="json")
     *
     * @var array
     */
    private $trad = [];

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

    public function setTrad(array $trad): self
    {
        $this->trad = $trad;

        return $this;
    }
}
