<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Service\AuditTrail\AuditrailableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'company_list']),
        new Get(normalizationContext: ['groups' => 'company_show']),
    ]
)]
class Company implements AuditrailableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['company_list', 'company_show', 'contact_show'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['opportunity_list', 'company_list', 'company_show', 'contact_show'])]
    private string $name;

    #[ORM\Column(length: 100)]
    #[Groups(['company_show'])]
    private string $street1;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['company_show'])]
    private ?string $street2;

    #[ORM\Column(length: 55)]
    #[Groups(['company_show'])]
    private string $city;

    #[ORM\Column(length: 10)]
    #[Groups(['company_show'])]
    private string $postcode;

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

    public function getAuditTrailString(): string
    {
        return $this->getName();
    }

    public function getFieldsToBeIgnored(): array
    {
        return [];
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

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
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
