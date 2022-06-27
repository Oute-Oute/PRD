<?php

namespace App\Entity;

use App\Repository\MRSARepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MRSARepository::class)
 */
class MRSA
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MaterialResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $materialresource;

    /**
     * @ORM\ManyToOne(targetEntity=ScheduledActivity::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $scheduledactivity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaterialresource(): ?MaterialResource
    {
        return $this->materialresource;
    }

    public function setMaterialresource(?MaterialResource $materialresource): self
    {
        $this->materialresource = $materialresource;

        return $this;
    }

    public function getScheduledactivity(): ?ScheduledActivity
    {
        return $this->scheduledactivity;
    }

    public function setScheduledactivity(?ScheduledActivity $scheduledactivity): self
    {
        $this->scheduledactivity = $scheduledactivity;

        return $this;
    }
}
