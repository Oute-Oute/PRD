<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Resource::class, inversedBy="events")
     */
    private $resource;

    /**
     * @ORM\ManyToMany(targetEntity=Circuit::class, inversedBy="events")
     */
    private $circuits;

    public function __construct()
    {
        $this->resource = new ArrayCollection();
        $this->circuits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResource(): Collection
    {
        return $this->resource;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resource->contains($resource)) {
            $this->resource[] = $resource;
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        $this->resource->removeElement($resource);

        return $this;
    }

    /**
     * @return Collection<int, Circuit>
     */
    public function getCircuits(): Collection
    {
        return $this->circuits;
    }

    public function addCircuit(Circuit $circuit): self
    {
        if (!$this->circuits->contains($circuit)) {
            $this->circuits[] = $circuit;
        }

        return $this;
    }

    public function removeCircuit(Circuit $circuit): self
    {
        $this->circuits->removeElement($circuit);

        return $this;
    }
}
