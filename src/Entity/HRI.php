<?php

namespace App\Entity;

use App\Repository\HRIRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HRIRepository::class)
 */
class HRI
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
     * @ORM\ManyToOne(targetEntity=Indisponibilities::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $indisponibility;

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

    public function getIndisponibility(): ?Indisponibilities
    {
        return $this->indisponibility;
    }

    public function setIndisponibility(?Indisponibilities $indisponibility): self
    {
        $this->indisponibility = $indisponibility;

        return $this;
    }
}
