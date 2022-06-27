<?php

namespace App\Entity;

use App\Repository\CHRRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CHRRepository::class)
 */
class CHR
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
     * @ORM\ManyToOne(targetEntity=CategoryHumanResource::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoryhumanresource;

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
