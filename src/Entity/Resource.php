<?php

namespace App\Entity;

use App\Repository\ResourceRepository;
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
    private $resourcename;

    /**
     * @ORM\Column(type="boolean")
     */
    private $able;

    /**
     * @ORM\ManyToOne(targetEntity=ResourceType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $resourcetype;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResourcename(): ?string
    {
        return $this->resourcename;
    }

    public function setResourcename(string $resourcename): self
    {
        $this->resourcename = $resourcename;

        return $this;
    }

    public function isAble(): ?bool
    {
        return $this->able;
    }

    public function setAble(bool $able): self
    {
        $this->able = $able;

        return $this;
    }

    public function getResourcetype(): ?ResourceType
    {
        return $this->resourcetype;
    }

    public function setResourcetype(?ResourceType $resourcetype): self
    {
        $this->resourcetype = $resourcetype;

        return $this;
    }
}
