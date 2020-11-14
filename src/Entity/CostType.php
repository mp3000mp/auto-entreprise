<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CostTypeRepository")
 */
class CostType
{
    public const TYPE_TAXES = 1;

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
    private $trad;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cost", mappedBy="type")
     *
     * @var ArrayCollection<int, Cost>
     */
    private $costs;

    public function __construct()
    {
        $this->costs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return ArrayCollection<int, Cost>
     */
    public function getCosts(): Collection
    {
        return $this->costs;
    }

    public function addCost(Cost $cost): self
    {
        if (!$this->costs->contains($cost)) {
            $this->costs[] = $cost;
            $cost->setType($this);
        }

        return $this;
    }

    public function removeCost(Cost $cost): self
    {
        if ($this->costs->contains($cost)) {
            $this->costs->removeElement($cost);
            // set the owning side to null (unless already changed)
            if ($cost->getType() === $this) {
                $cost->setType(null);
            }
        }

        return $this;
    }

    public function jsonize(): array
    {
        return [
            'trad' => $this->trad,
        ];
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
}
