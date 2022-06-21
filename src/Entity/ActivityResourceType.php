<?php

namespace App\Entity;

use App\Repository\ActivityResourceTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityResourceTypeRepository::class)
 */
class ActivityResourceType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Activity::class, cascade={"persist", "remove"})
     */
    private $activity_id;

    /**
     * @ORM\OneToOne(targetEntity=ResourceType::class, cascade={"persist", "remove"})
     */
    private $resource_type_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivityId(): ?Activity
    {
        return $this->activity_id;
    }

    public function setActivityId(?Activity $activity_id): self
    {
        $this->activity_id = $activity_id;

        return $this;
    }

    public function getResourceTypeId(): ?ResourceType
    {
        return $this->resource_type_id;
    }

    public function setResourceTypeId(?ResourceType $resource_type_id): self
    {
        $this->resource_type_id = $resource_type_id;

        return $this;
    }
}
