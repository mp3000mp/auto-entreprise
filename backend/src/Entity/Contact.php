<?php

namespace App\Entity;

use App\Service\AuditTrail\AuditrailableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[UniqueEntity(fields: 'email')]
class Contact implements AuditrailableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private string $last_name;

    #[ORM\Column(length: 55)]
    private string $first_name;

    #[ORM\Column(length: 55, unique: true)]
    private string $email;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $phone = null;

    // todo old companies
    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    /**
     * @var ArrayCollection<int, Opportunity>
     */
    #[ORM\ManyToMany(targetEntity: Opportunity::class, mappedBy: 'contacts')]
    private Collection $opportunities;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comments = null;

    public function __construct()
    {
        $this->opportunities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuditTrailString(): string
    {
        return $this->getEmail();
    }

    public function getFieldsToBeIgnored(): array
    {
        return [];
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Opportunity>
     */
    public function getOpportunities(): Collection
    {
        return $this->opportunities;
    }

    public function addOpportunity(Opportunity $opportunity): self
    {
        $this->opportunities->add($opportunity);

        return $this;
    }

    public function removeOpportunity(Opportunity $opportunity): self
    {
        $this->opportunities->removeElement($opportunity);

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
