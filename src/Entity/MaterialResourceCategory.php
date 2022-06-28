<?php

namespace App\Entity;

use App\Repository\MaterialResourceCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterialResourceCategoryRepository::class)
 */
class MaterialResourceCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categoryname;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryname(): ?string
    {
        return $this->categoryname;
    }

    public function setCategoryname(string $categoryname): self
    {
        $this->categoryname = $categoryname;

        return $this;
    }
}
