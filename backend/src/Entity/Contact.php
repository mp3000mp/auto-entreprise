<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(ContactRepository::class)]
#[UniqueEntity(fields: 'email')]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'contact_list']),
        new Get(requirements: ['id' => '\d+'], paginationEnabled: false, normalizationContext: ['groups' => 'contact_show']),
        new Post(normalizationContext: ['groups' => 'contact_show'], denormalizationContext: ['groups' => 'contact_write']),
        new Put(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'contact_show'], denormalizationContext: ['groups' => 'contact_write']),
    ]
)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contact_list', 'contact_show', 'company_show', 'opportunity_show'])]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    #[Groups(['contact_list', 'contact_show', 'company_show', 'opportunity_show', 'contact_write'])]
    #[Assert\NotBlank]
    private string $lastName;

    #[ORM\Column(length: 55)]
    #[Groups(['contact_list', 'contact_show', 'company_show', 'opportunity_show', 'contact_write'])]
    #[Assert\NotBlank]
    private string $firstName;

    #[ORM\Column(length: 55, unique: true)]
    #[Groups(['contact_list', 'contact_show', 'company_show', 'opportunity_show', 'contact_write'])]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['contact_list', 'contact_show', 'company_show', 'opportunity_show', 'contact_write'])]
    private ?string $phone = null;

    // todo company history
    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['contact_list', 'contact_show', 'contact_write'])]
    private Company $company;

    /**
     * @var ArrayCollection<int, Opportunity>
     */
    #[ORM\ManyToMany(targetEntity: Opportunity::class, mappedBy: 'contacts')]
    #[Groups(['contact_show'])]
    private Collection $opportunities;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['contact_show', 'contact_list'])]
    private ?string $comments = null;

    public function __construct()
    {
        $this->opportunities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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
