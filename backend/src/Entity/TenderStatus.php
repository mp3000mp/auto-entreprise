<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'tender_status_list']),
    ]
)]
class TenderStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tender_status_list', 'tender_show', 'tender_list', 'opportunity_list', 'company_show', 'contact_show'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['tender_status_list', 'tender_show', 'opportunity_list', 'company_show', 'contact_show'])]
    #[Assert\GreaterThan(0)]
    private int $position;

    #[ORM\Column(length: 55)]
    #[Groups(['tender_status_list', 'tender_show', 'tender_list', 'opportunity_list', 'company_show', 'contact_show'])]
    #[Assert\NotBlank]
    private string $label;

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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
