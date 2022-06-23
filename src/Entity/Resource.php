<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResourceRepository::class)
 */
class Resource
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
     * @ORM\ManyToOne(targetEntity=ResourceType::class, inversedBy="resources")
     */
    private $resource_type;

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

    public function getResourceType(): ?ResourceType
    {
        return $this->resource_type;
    }

    public function setResourceType(?ResourceType $resource_type): self
    {
        $this->resource_type = $resource_type;

        return $this;
    }

    public function __toString(): ?string 
    {
        return $this->getName();
    }
}
