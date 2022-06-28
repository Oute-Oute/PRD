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
     * @ORM\Column(type="time")
     */
    private $starttime;

    /**
     * @ORM\Column(type="time")
     */
    private $endtime;

    /**
     * @ORM\Column(type="date")
     */
    private $dayscheduled;

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

    public function getStarttime(): ?\DateTimeInterface
    {
        return $this->starttime;
    }

    public function setStarttime(\DateTimeInterface $starttime): self
    {
        $this->starttime = $starttime;

        return $this;
    }

    public function getEndtime(): ?\DateTimeInterface
    {
        return $this->endtime;
    }

    public function setEndtime(\DateTimeInterface $endtime): self
    {
        $this->endtime = $endtime;

        return $this;
    }

    public function getDayscheduled(): ?\DateTimeInterface
    {
        return $this->dayscheduled;
    }

    public function setDayscheduled(\DateTimeInterface $dayscheduled): self
    {
        $this->dayscheduled = $dayscheduled;

        return $this;
    }
}
