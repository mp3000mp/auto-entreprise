<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(CompanyRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: false, normalizationContext: ['groups' => 'company_list']),
        new Get(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'company_show']),
        new Post(normalizationContext: ['groups' => 'company_show'], denormalizationContext: ['groups' => 'company_write']),
        new Put(requirements: ['id' => '\d+'], normalizationContext: ['groups' => 'company_show'], denormalizationContext: ['groups' => 'company_write']),
    ],
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['company_list', 'company_show', 'contact_list', 'contact_show'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['opportunity_list', 'company_list', 'company_show', 'contact_list', 'contact_show', 'company_write'])]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(length: 100)]
    #[Groups(['company_show', 'company_write'])]
    #[Assert\NotBlank]
    private string $street1;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['company_show', 'company_write'])]
    private ?string $street2;

    #[ORM\Column(length: 55)]
    #[Groups(['company_show', 'company_write'])]
    #[Assert\NotBlank]
    private string $city;

    #[ORM\Column(length: 10)]
    #[Groups(['company_show', 'company_write'])]
    #[Assert\NotBlank]
    private string $postCode;

    /**
     * @var ArrayCollection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'company')]
    #[Groups(['company_show'])]
    private Collection $contacts;

    /**
     * @var ArrayCollection<int, Opportunity>
     */
    #[ORM\OneToMany(targetEntity: Opportunity::class, mappedBy: 'company')]
    #[Groups(['company_show'])]
    private Collection $opportunities;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->opportunities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStreet1(): string
    {
        return $this->street1;
    }

    public function setStreet1(string $street1): self
    {
        $this->street1 = $street1;

        return $this;
    }

    public function getStreet2(): ?string
    {
        return $this->street2;
    }

    public function setStreet2(?string $street2): self
    {
        $this->street2 = $street2;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function add(Contact $contact): self
    {
        $this->contacts->add($contact);

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        $this->contacts->removeElement($contact);

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
}
