<?php

namespace App\Entity;

use App\Repository\ActivityMaterialResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityMaterialResourceRepository::class)
 */
class ActivityMaterialResource
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
     * @ORM\ManyToOne(targetEntity=MaterialResourceCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $materialresourcecategory;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

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

    public function getMaterialresourcecategory(): ?MaterialResourceCategory
    {
        return $this->materialresourcecategory;
    }

    public function setMaterialresourcecategory(?MaterialResourceCategory $materialresourcecategory): self
    {
        $this->materialresourcecategory = $materialresourcecategory;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
