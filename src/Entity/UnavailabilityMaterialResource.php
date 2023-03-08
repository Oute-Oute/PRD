<?php

namespace App\Entity;

use App\Repository\UnavailabilityMaterialResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnavailabilityMaterialResourceRepository::class)
 */
class UnavailabilityMaterialResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MaterialResource::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $materialresource;

    /**
     * @ORM\ManyToOne(targetEntity=Unavailability::class)
     * @ORM\JoinColumn(nullable=false,)
     */
    private $unavailability;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaterialresource(): ?MaterialResource
    {
        return $this->materialresource;
    }

    public function setMaterialresource(?MaterialResource $materialresource): self
    {
        $this->materialresource = $materialresource;

        return $this;
    }

    public function getUnavailability(): ?Unavailability
    {
        return $this->unavailability;
    }

    public function setUnavailability(?Unavailability $unavailability): self
    {
        $this->unavailability = $unavailability;

        return $this;
    }
}