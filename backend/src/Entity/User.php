<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[UniqueEntity(fields: 'email')]
#[UniqueEntity(fields: 'username')]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'user_list']),
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_list', 'me'])]
    private ?int $id = null;

    #[ORM\Column(length: 55, unique: true)]
    #[Groups(['user_list', 'me'])]
    private string $email;

    #[ORM\Column(length: 55, unique: true)]
    #[Groups(['user_list', 'me'])]
    private string $username;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    #[Groups(['user_list', 'me'])]
    private array $roles = [];

    /**
     * @var ArrayCollection<int, WorkedTime>
     */
    #[ORM\OneToMany(targetEntity: WorkedTime::class, mappedBy: 'user')]
    #[ORM\OrderBy(['date' => 'DESC'])]
    private Collection $workedTimes;

    public function __construct()
    {
        $this->workedTimes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): self
    {
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @return ArrayCollection<int, WorkedTime>
     */
    public function getWorkedTimes(): Collection
    {
        return $this->workedTimes;
    }

    public function addWorkedTime(WorkedTime $workedTime): self
    {
        $this->workedTimes->add($workedTime);

        return $this;
    }

    public function removeWorkedTime(WorkedTime $workedTime): self
    {
        $this->workedTimes->removeElement($workedTime);

        return $this;
    }
}
