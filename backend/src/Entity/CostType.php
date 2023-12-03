<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\CostTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'cost_type_list']),
    ]
)]
class CostType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cost_type_list', 'cost_list'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['cost_type_list'])]
    #[Assert\GreaterThan(0)]
    private int $position;

    #[ORM\Column(length: 55)]
    #[Groups(['cost_type_list', 'cost_list'])]
    #[Assert\NotBlank]
    private string $label;

    #[ORM\Column(length: 55, unique: true, enumType: CostTypeEnum::class)]
    #[Groups(['cost_type_list'])]
    #[Assert\NotBlank]
    private CostTypeEnum $code;

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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public  function getCode(): CostTypeEnum
    {
        return $this->code;
    }

    public  function setCode(CostTypeEnum $code): self
    {
        $this->code = $code;

        return $this;
    }
}
