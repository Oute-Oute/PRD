<?php

namespace App\Entity;

use App\Repository\IMRRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IMRRepository::class)
 */
class IMR
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
    private $indisponibility;

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

    public function getIndisponibility(): ?Indisponibilities
    {
        return $this->indisponibility;
    }

    public function setIndisponibility(?Indisponibilities $indisponibility): self
    {
        $this->indisponibility = $indisponibility;

        return $this;
    }
}
