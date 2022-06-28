<?php

namespace App\Entity;

use App\Repository\WorkingHoursRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkingHoursRepository::class)
 */
class WorkingHours
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $starttime;

    /**
     * @ORM\Column(type="time")
     */
    private $endtime;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayweek;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity=HumanResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $humanresource;

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

    public function getHumanresource(): ?HumanResource
    {
        return $this->humanresource;
    }

    public function setHumanresource(?HumanResource $humanresource): self
    {
        $this->humanresource = $humanresource;

        return $this;
    }

    public function getDayweek(): ?int
    {
        return $this->dayweek;
    }

    public function setDayweek(int $dayweek): self
    {
        $this->dayweek = $dayweek;

        return $this;
    }
}
