<?php

namespace App\Service\AuditTrail;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractAuditTrailEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    protected ?User $user;

    #[ORM\Column]
    protected \DateTime $date;

    #[ORM\Column(length: 55)]
    protected ?string $reason;

    // todo use AuditTrailModifTypeEnum ?
    #[ORM\Column(options: ['comment' => '1=insert, 2=update, 3=delete'])]
    protected int $modif_type;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: 'json', nullable: true)]
    protected ?array $details;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getModifType(): int
    {
        return $this->modif_type;
    }

    public function setModifType(int $modif_type): self
    {
        $this->modif_type = $modif_type;

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @param array<string, mixed>|null $details
     */
    public function setDetails(?array $details): self
    {
        $this->details = $details;

        return $this;
    }
}
