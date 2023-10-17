<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[UniqueEntity(fields: 'email')]
#[UniqueEntity(fields: 'username')]
class User implements UserInterface, \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55, unique: true)]
    private string $email;

    #[ORM\Column(length: 55, unique: true)]
    private string $username;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
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

    public function getSalt(): ?string
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles,
            // see section on salt below
            // $this->salt,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, ['allowed_classes' => false]);
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
