<?php

namespace App\Entity;

use App\Repository\IndisponibilitiesMaterialResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndisponibilitiesMaterialResourceRepository::class)
 */
class IndisponibilitiesMaterialResource
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
     * @ORM\ManyToOne(targetEntity=Indisponibilities::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $indisponibilities;

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

    public function getIndisponibilities(): ?Indisponibilities
    {
        return $this->indisponibilities;
    }

    public function setIndisponibilities(?Indisponibilities $indisponibilities): self
    {
        $this->indisponibilities = $indisponibilities;

        return $this;
    }
}
