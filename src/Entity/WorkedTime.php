<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkedTimeRepository")
 */
class WorkedTime
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
     * @ORM\Column(type="float")
     *
     * @var float
     */
    private $workedDays;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tender", inversedBy="workedTimes")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Tender
     */
    private $tender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="workedTimes")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var User
     */
    private $user;

    /**
     * @Assert\LessThanOrEqual("now")
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkedDays(): ?float
    {
        return $this->workedDays;
    }

    public function setWorkedDays(float $workedDays): self
    {
        $this->workedDays = $workedDays;

        return $this;
    }

    public function getTender(): ?Tender
    {
        return $this->tender;
    }

    public function setTender(?Tender $tender): self
    {
        $this->tender = $tender;

        return $this;
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

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function jsonize(): array
    {
        return [
            'id' => $this->id,
            'workedDays' => $this->workedDays,
            'date' => $this->date->format('Y-m-d'),
            'user' => [
                'id' => $this->user->getId(),
                'fullName' => $this->user->getFullName(),
            ],
            'tender' => $this->tender->getId(),
        ];
    }
}
