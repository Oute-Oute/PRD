<?php

namespace App\Entity;

use App\Repository\UnavailabilityHumanResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnavailabilityHumanResourceRepository::class)
 */
class UnavailabilityHumanResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=HumanResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $humanresource;

    /**
     * @ORM\ManyToOne(targetEntity=Unavailability::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unavailability;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHumanresource(): ?HumanResource
    {
        return $this->humanresource;
    }

    public function setHumanresource(?HumanResource $humanresource): self
    {
        $this->humanresource = $humanresource;

        return $this;
    }

    public function getUnavailability(): ?Unavailability
    {
        return $this->unavailability;
    }

    public function setUnavailability(?Unavailability $unavailability): self
    {
        $this->unavailability = $unavailability;

        return $this;
    }
}
