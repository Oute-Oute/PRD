<?php

namespace App\Entity;

use App\Repository\ModificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModificationRepository::class)
 */
class Modification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $datemodified;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetimemodification;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatemodified(): ?\DateTimeInterface
    {
        return $this->datemodified;
    }

    public function setDatemodif(\DateTimeInterface $datemodified): self
    {
        $this->datemodified = $datemodified;

        return $this;
    }

    public function getDatetimemodification(): ?\DateTimeInterface
    {
        return $this->datetimemodification;
    }

    public function setDatetimemodification(\DateTimeInterface $datetimemodification): self
    {
        $this->datetimemodification = $datetimemodification;

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
}
