<?php

namespace App\Entity;

use App\Repository\TargetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TargetRepository::class)
 */
class Target
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $target;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayweek;

    /**
     * @ORM\ManyToOne(targetEntity=Pathway::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pathway;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarget(): ?int
    {
        return $this->target;
    }

    public function setTarget(int $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getDayweek(): ?int
    {
        return $this->dayweek;
    }

    public function setDayweek(int $dayweek): self
    {
        $this->dayweek = $dayweek;

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
