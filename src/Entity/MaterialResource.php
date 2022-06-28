<?php

namespace App\Entity;

use App\Repository\MaterialResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterialResourceRepository::class)
 */
class MaterialResource
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
    private $materialresourcename;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaterialresourcename(): ?string
    {
        return $this->materialresourcename;
    }

    public function setMaterialresourcename(string $materialresourcename): self
    {
        $this->materialresourcename = $materialresourcename;

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
}
