<?php

namespace App\Entity;

use App\Enum\TenderFileTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class TenderFile implements FileInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tender_show'])]
    private ?int $id = null;

    #[ORM\Column(length: 12, enumType: TenderFileTypeEnum::class)]
    #[Groups(['tender_show'])]
    private TenderFileTypeEnum $type;

    #[ORM\Column(length: 255)]
    #[Groups(['tender_show'])]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(length: 500)]
    #[Groups(['tender_show'])]
    #[Assert\NotBlank]
    private string $extension;

    #[ORM\Column(length: 255)]
    private string $path;

    #[ORM\ManyToOne(targetEntity: Tender::class, inversedBy: 'tenderFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private Tender $tender;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\Column]
    #[Groups(['tender_show'])]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): TenderFileTypeEnum
    {
        return $this->type;
    }

    public function setType(TenderFileTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getTender(): Tender
    {
        return $this->tender;
    }

    public function setTender(Tender $tender): self
    {
        $this->tender = $tender;

        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
