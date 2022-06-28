<?php

namespace App\Entity;

use App\Repository\ActivityHumanResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityHumanResourceRepository::class)
 */
class ActivityHumanResource
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
     * @ORM\ManyToOne(targetEntity=HumanResourceCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $humanresourcecategory;

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

    public function getHumanresourcecategory(): ?HumanResourceCategory
    {
        return $this->humanresourcecategory;
    }

    public function setHumanresourcecategory(?HumanResourceCategory $humanresourcecategory): self
    {
        $this->humanresourcecategory = $humanresourcecategory;

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
