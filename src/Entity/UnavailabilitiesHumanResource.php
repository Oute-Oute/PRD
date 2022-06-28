<?php

namespace App\Entity;

use App\Repository\UnavailabilitiesHumanResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnavailabilitiesHumanResourceRepository::class)
 */
class UnavailabilitiesHumanResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Unavailabilities::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unavailabilities;

    /**
     * @ORM\ManyToOne(targetEntity=HumanResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $humanresource;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnavailabilities(): ?Unavailabilities
    {
        return $this->unavailabilities;
    }

    public function setUnavailabilities(?Unavailabilities $unavailabilities): self
    {
        $this->unavailabilities = $unavailabilities;

        return $this;
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
}
