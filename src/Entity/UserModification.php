<?php

namespace App\Entity;

use App\Repository\UserModificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserModificationRepository::class)
 */
class UserModification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user_id;

    /**
     * @ORM\OneToOne(targetEntity=Modification::class, cascade={"persist", "remove"})
     */
    private $modification_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getModificationId(): ?Modification
    {
        return $this->modification_id;
    }

    public function setModificationId(?Modification $modification_id): self
    {
        $this->modification_id = $modification_id;

        return $this;
    }
}
