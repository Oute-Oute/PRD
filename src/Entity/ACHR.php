<?php

namespace App\Entity;

use App\Repository\ACHRRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ACHRRepository::class)
 */
class ACHR
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
     * @ORM\ManyToOne(targetEntity=CategoryHumanResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoryhumanresource;

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

    public function getCategoryhumanresource(): ?CategoryHumanResource
    {
        return $this->categoryhumanresource;
    }

    public function setCategoryhumanresource(?CategoryHumanResource $categoryhumanresource): self
    {
        $this->categoryhumanresource = $categoryhumanresource;

        return $this;
    }
}
