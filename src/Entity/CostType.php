<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CostTypeRepository")
 */
class CostType
{
	
	const TYPE_TAXES = 1;
	
	
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	private $position;

    /**
     * @ORM\Column(type="json_array")
     */
    private $trad;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cost", mappedBy="type")
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

    public function getTrad()
    {
        return $this->trad;
    }

    public function setTrad($trad): self
    {
        $this->trad = $trad;

        return $this;
    }

    /**
     * @return Collection|Cost[]
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
	
	/**
	 * @return array
	 */
    public function jsonize():array
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
