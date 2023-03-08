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

    public function __toString()
    {
        return $this->getPathwayname();
    }
}