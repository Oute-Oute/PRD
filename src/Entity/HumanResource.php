<?php

namespace App\Entity;

use App\Repository\HumanResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HumanResourceRepository::class)
 */
class HumanResource
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
    private $humanresourcename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHumanresourcename(): ?string
    {
        return $this->humanresourcename;
    }

    public function setHumanresourcename(string $humanresourcename): self
    {
        $this->humanresourcename = $humanresourcename;

        return $this;
    }

    public function __toString()
    {
        return $this->getHumanresourcename();
    } 
}
