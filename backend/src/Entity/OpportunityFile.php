<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class OpportunityFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['opportunity_show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['opportunity_show'])]
    private string $title;

    #[ORM\Column(length: 500)]
    #[Groups(['opportunity_show'])]
    private string $description;

    #[ORM\Column(length: 255)]
    private string $path;

    #[ORM\ManyToOne(targetEntity: Opportunity::class, inversedBy: 'opportunityFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private Opportunity $opportunity;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\Column]
    #[Groups(['opportunity_show'])]
    private \DateTime $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getOpportunity(): Opportunity
    {
        return $this->opportunity;
    }

    public function setOpportunity(Opportunity $opportunity): self
    {
        $this->opportunity = $opportunity;

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

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
