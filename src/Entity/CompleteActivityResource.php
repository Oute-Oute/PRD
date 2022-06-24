<?php

namespace App\Entity;

use App\Repository\CompleteActivityResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompleteActivityResourceRepository::class)
 */
class CompleteActivityResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CompleteActivity::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $completeactivity;

    /**
     * @ORM\ManyToOne(targetEntity=Resource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompleteactivity(): ?CompleteActivity
    {
        return $this->completeactivity;
    }

    public function setCompleteactivity(?CompleteActivity $completeactivity): self
    {
        $this->completeactivity = $completeactivity;

        return $this;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $resource): self
    {
        $this->resource = $resource;

        return $this;
    }
}
