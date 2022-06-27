<?php

namespace App\Entity;

use App\Repository\IndisponibilitiesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndisponibilitiesRepository::class)
 */
class Indisponibilities
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
    private $enddatetime;

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

    public function getEnddatetime(): ?\DateTimeInterface
    {
        return $this->enddatetime;
    }

    public function setEnddatetime(\DateTimeInterface $enddatetime): self
    {
        $this->enddatetime = $enddatetime;

        return $this;
    }
}
