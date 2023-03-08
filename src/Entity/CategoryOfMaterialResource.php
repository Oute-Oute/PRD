<?php

namespace App\Entity;

use App\Repository\CategoryOfMaterialResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryOfMaterialResourceRepository::class)
 */
class CategoryOfMaterialResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MaterialResource::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $materialresource;

    /**
     * @ORM\ManyToOne(targetEntity=MaterialResourceCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $materialresourcecategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaterialresource(): ?MaterialResource
    {
        return $this->materialresource;
    }

    public function setMaterialresource(?MaterialResource $materialresource): self
    {
        $this->materialresource = $materialresource;

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
}