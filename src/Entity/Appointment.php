<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppointmentRepository::class)
 */
class Appointment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $earliestappointmenttime;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $latestappointmenttime;

    /**
     * @ORM\Column(type="date")
     */
    private $dayappointment;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity=Pathway::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pathway;

    /**
     * @ORM\Column(type="boolean")
     */
    private $scheduled;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEarliestappointmenttime(): ?\DateTimeInterface
    {
        return $this->earliestappointmenttime;
    }

    public function setEarliestappointmenttime(?\DateTimeInterface $earliestappointmenttime): self
    {
        $this->earliestappointmenttime = $earliestappointmenttime;

        return $this;
    }

    public function getLatestappointmenttime(): ?\DateTimeInterface
    {
        return $this->latestappointmenttime;
    }

    public function setLatestappointmenttime(?\DateTimeInterface $latestappointmenttime): self
    {
        $this->latestappointmenttime = $latestappointmenttime;

        return $this;
    }

    public function getDayappointment(): ?\DateTimeInterface
    {
        return $this->dayappointment;
    }

    public function setDayappointment(\DateTimeInterface $dayappointment): self
    {
        $this->dayappointment = $dayappointment;

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

    public function getPathway(): ?Pathway
    {
        return $this->pathway;
    }

    public function setPathway(?Pathway $pathway): self
    {
        $this->pathway = $pathway;

        return $this;
    }

    public function isScheduled(): ?bool
    {
        return $this->scheduled;
    }

    public function setScheduled(bool $scheduled): self
    {
        $this->scheduled = $scheduled;

        return $this;
    }
}