<?php

namespace App\Entity;

use App\Repository\CategoryOfHumanResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryOfHumanResourceRepository::class)
 */
class CategoryOfHumanResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=HumanResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $humanresource;

    /**
     * @ORM\ManyToOne(targetEntity=HumanResourceCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $humanresourcecategory;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHumanresourcecategory(): ?HumanResourceCategory
    {
        return $this->humanresourcecategory;
    }

    public function setHumanresourcecategory(?HumanResourceCategory $humanresourcecategory): self
    {
        $this->humanresourcecategory = $humanresourcecategory;

        return $this;
    }
}
