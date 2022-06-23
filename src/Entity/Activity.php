<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
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
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\ManyToMany(targetEntity=Circuit::class, inversedBy="activities")
     */
    private $circuits;

    /**
     * @ORM\ManyToMany(targetEntity=ResourceType::class, inversedBy="activities")
     */
    private $resourcetypes;

    public function __construct()
    {
        $this->circuits = new ArrayCollection();
        $this->resourcetypes = new ArrayCollection();
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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

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

    /**
     * @return Collection<int, ResourceType>
     */
    public function getResourceTypes(): Collection
    {
        return $this->resourcetypes;
    }

    public function addResourceType(ResourceType $resourcetype): self
    {
        if (!$this->resourcetypes->contains($resourcetype)) {
            $this->resourcetypes[] = $resourcetype;
        }

        return $this;
    }

    public function removeResourceType(ResourceType $resourcetype): self
    {
        $this->resourcetypes->removeElement($resourcetype);

        return $this;
    }

    public function __toString(): ?string 
    {
        return $this->getName() . "  / " . $this->getDuration();
    }

}
