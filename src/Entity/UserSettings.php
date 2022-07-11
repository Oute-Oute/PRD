<?php

namespace App\Entity;

use App\Repository\UserSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserSettingsRepository::class)
 */
class UserSettings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $zoommultiplier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZoommultiplier(): ?int
    {
        return $this->zoommultiplier;
    }

    public function setZoommultiplier(int $zoommultiplier): self
    {
        $this->zoommultiplier = $zoommultiplier;

        return $this;
    }
}
