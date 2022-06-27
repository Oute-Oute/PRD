<?php

namespace App\Entity;

use App\Repository\ACMRRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ACMRRepository::class)
 */
class ACMR
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
     * @ORM\ManyToOne(targetEntity=CategoryMaterialResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorymaterialresource;

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

    public function getCategorymaterialresource(): ?CategoryMaterialResource
    {
        return $this->categorymaterialresource;
    }

    public function setCategorymaterialresource(?CategoryMaterialResource $categorymaterialresource): self
    {
        $this->categorymaterialresource = $categorymaterialresource;

        return $this;
    }
}
