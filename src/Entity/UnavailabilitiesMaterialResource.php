<?php

namespace App\Entity;

use App\Repository\UnavailabilitiesMaterialResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnavailabilitiesMaterialResourceRepository::class)
 */
class UnavailabilitiesMaterialResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MaterialResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $materialresource;

    /**
     * @ORM\ManyToOne(targetEntity=Unavailabilities::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unavailabilities;

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

    public function getUnavailabilities(): ?Unavailabilities
    {
        return $this->unavailabilities;
    }

    public function setUnavailabilities(?Unavailabilities $unavailabilities): self
    {
        $this->unavailabilities = $unavailabilities;

        return $this;
    }
}
