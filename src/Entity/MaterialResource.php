<?php

namespace App\Entity;

use App\Repository\MaterialResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterialResourceRepository::class)
 */
class MaterialResource
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
    private $materialresourcename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaterialresourcename(): ?string
    {
        return $this->materialresourcename;
    }

    public function setMaterialresourcename(string $materialresourcename): self
    {
        $this->materialresourcename = $materialresourcename;

        return $this;
    }
}
