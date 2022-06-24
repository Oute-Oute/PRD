<?php

namespace App\Entity;

use App\Repository\CircuitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CircuitRepository::class)
 */
class Circuit
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
    private $circuitname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $circuittype;

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

    public function getCircuitname(): ?string
    {
        return $this->circuitname;
    }

    public function setCircuitname(string $circuitname): self
    {
        $this->circuitname = $circuitname;

        return $this;
    }

    public function getCircuittype(): ?string
    {
        return $this->circuittype;
    }

    public function setCircuittype(string $circuittype): self
    {
        $this->circuittype = $circuittype;

        return $this;
    }
}
