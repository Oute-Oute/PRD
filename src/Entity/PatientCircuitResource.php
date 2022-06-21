<?php

namespace App\Entity;

use App\Repository\PatientCircuitResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PatientCircuitResourceRepository::class)
 */
class PatientCircuitResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Patient::class, cascade={"persist", "remove"})
     */
    private $patient_id;

    /**
     * @ORM\OneToOne(targetEntity=Circuit::class, cascade={"persist", "remove"})
     */
    private $circuit_id;

    /**
     * @ORM\OneToOne(targetEntity=Resource::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_date_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_date_time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatientId(): ?Patient
    {
        return $this->patient_id;
    }

    public function setPatientId(?Patient $patient_id): self
    {
        $this->patient_id = $patient_id;

        return $this;
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

    public function getResourceId(): ?Resource
    {
        return $this->resource_id;
    }

    public function setResourceId(Resource $resource_id): self
    {
        $this->resource_id = $resource_id;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->start_date_time;
    }

    public function setStartDateTime(\DateTimeInterface $start_date_time): self
    {
        $this->start_date_time = $start_date_time;

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeInterface
    {
        return $this->end_date_time;
    }

    public function setEndDateTime(\DateTimeInterface $end_date_time): self
    {
        $this->end_date_time = $end_date_time;

        return $this;
    }
}
