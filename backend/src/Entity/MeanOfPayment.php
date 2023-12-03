<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\MeanOfPaymentEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'mop_list']),
    ]
)]
class MeanOfPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['mop_list', 'opportunity_show'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['mop_list'])]
    #[Assert\GreaterThan(0)]
    private int $position;

    #[ORM\Column(length: 55)]
    #[Groups(['mop_list', 'opportunity_show'])]
    #[Assert\NotBlank]
    private string $label;

    #[ORM\Column(length: 55, unique: true, enumType: MeanOfPaymentEnum::class)]
    #[Groups(['mop_list'])]
    #[Assert\NotBlank]
    private MeanOfPaymentEnum $code;

    public function __construct()
    {
    }

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

    public  function getCode(): MeanOfPaymentEnum
    {
        return $this->code;
    }

    public  function setCode(MeanOfPaymentEnum $code): self
    {
        $this->code = $code;

        return $this;
    }
}
