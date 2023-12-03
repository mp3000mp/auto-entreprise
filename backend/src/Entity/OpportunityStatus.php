<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\OpportunityStatusEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'opportunity_status_list']),
    ]
)]
class OpportunityStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['opportunity_status_list', 'opportunity_list', 'opportunity_show', 'company_show', 'contact_show'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['opportunity_status_list'])]
    #[Assert\GreaterThan(0)]
    private int $position;

    #[ORM\Column(length: 55)]
    #[Groups(['opportunity_status_list', 'opportunity_list', 'opportunity_show', 'company_show', 'contact_show'])]
    #[Assert\NotBlank]
    private string $label;

    #[ORM\Column(length: 55, unique: true, enumType: OpportunityStatusEnum::class)]
    #[Groups(['opportunity_status_list'])]
    #[Assert\NotBlank]
    private OpportunityStatusEnum $code;

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

    public  function getCode(): OpportunityStatusEnum
    {
        return $this->code;
    }

    public  function setCode(OpportunityStatusEnum $code): self
    {
        $this->code = $code;

        return $this;
    }
}
