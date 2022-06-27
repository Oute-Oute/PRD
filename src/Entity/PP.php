<?php

namespace App\Entity;

use App\Repository\PPRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PPRepository::class)
 */
class PP
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity=Pathway::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pathway;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

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
}
