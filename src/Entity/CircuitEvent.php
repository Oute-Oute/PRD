<?php

namespace App\Entity;

use App\Repository\CircuitEventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CircuitEventRepository::class)
 */
class CircuitEvent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Circuit::class, cascade={"persist", "remove"})
     */
    private $circuit_id;

    /**
     * @ORM\OneToOne(targetEntity=Activity::class, cascade={"persist", "remove"})
     */
    private $activity_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCircuitId(): ?Circuit
    {
        return $this->circuit_id;
    }

    public function setCircuitId(?Circuit $circuit_id): self
    {
        $this->circuit_id = $circuit_id;

        return $this;
    }

    public function getActivityId(): ?Activity
    {
        return $this->activity_id;
    }

    public function setActivityId(?Activity $activity_id): self
    {
        $this->activity_id = $activity_id;

        return $this;
    }
}
