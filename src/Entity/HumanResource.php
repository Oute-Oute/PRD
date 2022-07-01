<?php

namespace App\Entity;

use App\Repository\HumanResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HumanResourceRepository::class)
 */
class HumanResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $humanresourcename;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHumanresourcename(): ?string
    {
        return $this->humanresourcename;
    }

    public function setHumanresourcename(string $humanresourcename): self
    {
        $this->humanresourcename = $humanresourcename;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function __toString()
    {
        return $this->getHumanresourcename();
    }
}
