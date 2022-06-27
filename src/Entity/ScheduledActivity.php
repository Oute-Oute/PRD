<?php

namespace App\Entity;

use App\Repository\ScheduledActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScheduledActivityRepository::class)
 */
class ScheduledActivity
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
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startdatetime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $enddatetime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdatetime;
    }

    public function setStartdate(\DateTimeInterface $startdatetime): self
    {
        $this->startdatetime = $startdatetime;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddatetime;
    }

    public function setEnddate(\DateTimeInterface $enddatetime): self
    {
        $this->enddatetime = $enddatetime;

        return $this;
    }
}
