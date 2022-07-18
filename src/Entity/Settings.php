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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $alertmodificationtimer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zoommultiplier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reloadtime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlertmodificationtimer(): ?int
    {
        return $this->alertmodificationtimer;
    }

    public function setAlertmodificationtimer(?int $alertmodificationtimer): self
    {
        $this->alertmodificationtimer = $alertmodificationtimer;

        return $this;
    }

    public function getZoommultiplier(): ?int
    {
        return $this->zoommultiplier;
    }

    public function setZoommultiplier(?int $zoommultiplier): self
    {
        $this->zoommultiplier = $zoommultiplier;

        return $this;
    }

    public function getReloadtime(): ?int
    {
        return $this->reloadtime;
    }

    public function setReloadtime(?int $reloadtime): self
    {
        $this->reloadtime = $reloadtime;

        return $this;
    }
}
