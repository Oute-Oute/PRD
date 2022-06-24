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
     * @ORM\ManyToOne(targetEntity=Activity::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity=ResourceType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $resourcetype;

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

    public function getResourcetype(): ?ResourceType
    {
        return $this->resourcetype;
    }

    public function setResourcetype(?ResourceType $resourcetype): self
    {
        $this->resourcetype = $resourcetype;

        return $this;
    }
}
