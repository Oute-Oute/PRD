<?php

namespace App\Entity;

use App\Repository\UnavailabilitiesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnavailabilitiesRepository::class)
 */
class Unavailabilities
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startdatetime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $anddatetime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartdatetime(): ?\DateTimeInterface
    {
        return $this->startdatetime;
    }

    public function setStartdatetime(\DateTimeInterface $startdatetime): self
    {
        $this->startdatetime = $startdatetime;

        return $this;
    }

    public function getAnddatetime(): ?\DateTimeInterface
    {
        return $this->anddatetime;
    }

    public function setAnddatetime(\DateTimeInterface $anddatetime): self
    {
        $this->anddatetime = $anddatetime;

        return $this;
    }
}
