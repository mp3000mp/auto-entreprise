<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="entity.User.email.already_exists")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55, unique=true)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="smallint")
     *
     * @var int
     */
    private $nb_failed_connexion = 0;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $is_active = false;

    /**
     * @ORM\Column(type="string", length=55)
     *
     * @var string
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=55)
     *
     * @var string
     */
    private $last_name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    private $password_updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $reset_password_token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    private $reset_password_date;

    /**
     * @ORM\Column(type="json", nullable=false)
     *
     * @var array
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkedTime", mappedBy="user")
     *
     * @var ArrayCollection<int, WorkedTime>
     */
    private $workedTimes;

    /**
     * User constructor.
     */
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

    /**
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
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
     * @return User
     */
    public function setRoles(array $roles): self
    {
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        $this->roles = $roles;

        return $this;
    }

    public function getNbFailedConnexion(): ?int
    {
        return $this->nb_failed_connexion;
    }

    /**
     * @return User
     */
    public function setNbFailedConnexion(int $nb_failed_connexion): self
    {
        $this->nb_failed_connexion = $nb_failed_connexion;

        return $this;
    }

    /**
     * @return User
     */
    public function addNbFailedConnexion(): self
    {
        ++$this->nb_failed_connexion;

        return $this;
    }

    /**
     * @return User
     */
    public function razNbFailedConnexion(): self
    {
        $this->nb_failed_connexion = 0;

        return $this;
    }

    /**
     * @return User
     */
    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->is_active;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @return User
     */
    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @return User
     */
    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPasswordUpdatedAt(): ?DateTime
    {
        return $this->password_updated_at;
    }

    /**
     * @return User
     */
    public function setPasswordUpdatedAt(DateTime $password_updated_at): self
    {
        $this->password_updated_at = $password_updated_at;

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
            $this->password,
            $this->is_active,
            $this->roles,
            // see section on salt below
            // $this->salt,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->is_active,
            $this->roles,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function isAccountNonLocked(): bool
    {
        return $this->nb_failed_connexion <= 3;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->reset_password_token;
    }

    public function setResetPasswordToken(?string $reset_password_token): self
    {
        $this->reset_password_token = $reset_password_token;

        return $this;
    }

    public function getResetPasswordDate(): ?DateTime
    {
        return $this->reset_password_date;
    }

    public function setResetPasswordDate(?DateTime $reset_password_date): self
    {
        $this->reset_password_date = $reset_password_date;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * @return ArrayCollection<int, WorkedTime>
     */
    public function getWorkedTimes(): ArrayCollection
    {
        return $this->workedTimes;
    }

    public function addWorkedTime(WorkedTime $workedTime): self
    {
        if (!$this->workedTimes->contains($workedTime)) {
            $this->workedTimes[] = $workedTime;
            $workedTime->setUser($this);
        }

        return $this;
    }

    public function removeWorkedTime(WorkedTime $workedTime): self
    {
        if ($this->workedTimes->contains($workedTime)) {
            $this->workedTimes->removeElement($workedTime);
            // set the owning side to null (unless already changed)
            if ($workedTime->getUser() === $this) {
                $workedTime->setUser(null);
            }
        }

        return $this;
    }
}
