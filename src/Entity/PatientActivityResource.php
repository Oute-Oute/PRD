<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PatientActivityResourceRepository::class)
 */
class PatientActivityResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Patient::class)
     */
    private $patient;

    /**
     * @ORM\ManyToMany(targetEntity=Activity::class)
     */
    private $activity;

    /**
     * @ORM\ManyToMany(targetEntity=Resource::class)
     */
    private $resource;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_datetime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_datetime;

    public function __construct()
    {
        $this->patient = new ArrayCollection();
        $this->activity = new ArrayCollection();
        $this->resource = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Patient>
     */
    public function getPatient(): Collection
    {
        return $this->patient;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patient->contains($patient)) {
            $this->patient[] = $patient;
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        $this->patient->removeElement($patient);

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivity(): Collection
    {
        return $this->activity;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activity->contains($activity)) {
            $this->activity[] = $activity;
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        $this->activity->removeElement($activity);

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResource(): Collection
    {
        return $this->resource;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resource->contains($resource)) {
            $this->resource[] = $resource;
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        $this->resource->removeElement($resource);

        return $this;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->start_datetime;
    }

    public function setStartDatetime(\DateTimeInterface $start_datetime): self
    {
        $this->start_datetime = $start_datetime;

        return $this;
    }

    public function getEndDatetime(): ?\DateTimeInterface
    {
        return $this->end_datetime;
    }

    public function setEndDatetime(\DateTimeInterface $end_datetime): self
    {
        $this->end_datetime = $end_datetime;

        return $this;
    }
}
