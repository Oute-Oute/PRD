<?php

namespace App\Entity;

use App\Repository\PathwayRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PathwayRepository::class)
 */
class Pathway
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
    private $pathwayname;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPathwayname(): ?string
    {
        return $this->pathwayname;
    }

    public function setPathwayname(string $pathwayname): self
    {
        $this->pathwayname = $pathwayname;

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

    public function getPathway(): ?Pathway
    {
        return $this->pathway;
    }

    public function setPathway(?Pathway $pathway): self
    {
        $this->pathway = $pathway;

        return $this;
    }

    public function __toString()
    {
        return $this->getPathwayname();
    }
}
