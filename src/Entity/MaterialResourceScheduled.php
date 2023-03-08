<?php

namespace App\Entity;

use App\Repository\MaterialResourceScheduledRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterialResourceScheduledRepository::class)
 */
class MaterialResourceScheduled
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ScheduledActivity::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $scheduledactivity;

    /**
     * @ORM\ManyToOne(targetEntity=MaterialResource::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $materialresource;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMaterialresource(): ?MaterialResource
    {
        return $this->materialresource;
    }

    public function setMaterialresource(?MaterialResource $materialresource): self
    {
        $this->materialresource = $materialresource;

        return $this;
    }
}