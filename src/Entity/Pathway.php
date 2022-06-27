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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $target;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathwayname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathwaytype;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarget(): ?int
    {
        return $this->target;
    }

    public function setTarget(?int $target): self
    {
        $this->target = $target;

        return $this;
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

    public function getPathwaytype(): ?string
    {
        return $this->pathwaytype;
    }

    public function setPathwaytype(string $pathwaytype): self
    {
        $this->pathwaytype = $pathwaytype;

        return $this;
    }

    public function __toString()
    {
        return $this->getPathwayname();
    }
}
