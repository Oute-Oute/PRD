<?php

namespace App\Entity;

use App\Repository\APRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=APRepository::class)
 */
class AP
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Pathway::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pathway;

    /**
     * @ORM\ManyToOne(targetEntity=Activity::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    /**
     * @ORM\Column(type="integer")
     */
    private $activityorder;

    /**
     * @ORM\Column(type="integer")
     */
    private $delayminafter;

    /**
     * @ORM\Column(type="integer")
     */
    private $delaymaxafter;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getActivityorder(): ?int
    {
        return $this->activityorder;
    }

    public function setActivityorder(int $activityorder): self
    {
        $this->activityorder = $activityorder;

        return $this;
    }

    public function getDelayminafter(): ?int
    {
        return $this->delayminafter;
    }

    public function setDelayminafter(int $delayminafter): self
    {
        $this->delayminafter = $delayminafter;

        return $this;
    }

    public function getDelaymaxafter(): ?int
    {
        return $this->delaymaxafter;
    }

    public function setDelaymaxafter(int $delaymaxafter): self
    {
        $this->delaymaxafter = $delaymaxafter;

        return $this;
    }
}
