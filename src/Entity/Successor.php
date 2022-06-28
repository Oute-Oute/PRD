<?php

namespace App\Entity;

use App\Repository\SuccessorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SuccessorRepository::class)
 */
class Successor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Activity::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $activitya;

    /**
     * @ORM\ManyToOne(targetEntity=Activity::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $activityb;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $delaymin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $delaymax;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivitya(): ?Activity
    {
        return $this->activitya;
    }

    public function setActivitya(?Activity $activitya): self
    {
        $this->activitya = $activitya;

        return $this;
    }

    public function getActivityb(): ?Activity
    {
        return $this->activityb;
    }

    public function setActivityb(?Activity $activityb): self
    {
        $this->activityb = $activityb;

        return $this;
    }

    public function getDelaymin(): ?int
    {
        return $this->delaymin;
    }

    public function setDelaymin(?int $delaymin): self
    {
        $this->delaymin = $delaymin;

        return $this;
    }

    public function getDelaymax(): ?int
    {
        return $this->delaymax;
    }

    public function setDelaymax(?int $delaymax): self
    {
        $this->delaymax = $delaymax;

        return $this;
    }
}
