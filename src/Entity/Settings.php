<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 */
class Settings
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
    private $timer;

    /**
     * @ORM\Column(type="integer")
     */
    private $unittime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimer(): ?int
    {
        return $this->timer;
    }

    public function setTimer(int $timer): self
    {
        $this->timer = $timer;

        return $this;
    }

    public function getUnittime(): ?int
    {
        return $this->unittime;
    }

    public function setUnittime(int $unittime): self
    {
        $this->unittime = $unittime;

        return $this;
    }
}
