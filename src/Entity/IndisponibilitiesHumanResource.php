<?php

namespace App\Entity;

use App\Repository\IndisponibilitiesHumanResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndisponibilitiesHumanResourceRepository::class)
 */
class IndisponibilitiesHumanResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=HumanResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $humanresource;

    /**
     * @ORM\ManyToOne(targetEntity=Indisponibilities::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $indisponibilities;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHumanresource(): ?HumanResource
    {
        return $this->humanresource;
    }

    public function setHumanresource(?HumanResource $humanresource): self
    {
        $this->humanresource = $humanresource;

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
