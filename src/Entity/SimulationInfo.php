<?php

namespace App\Entity;

use App\Repository\SimulationInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SimulationInfoRepository::class)
 */
class SimulationInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $iscurrent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $simulationdatetime;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberofpatient;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberofhumanresource;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberofmaterialresource;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isCurrent(): ?bool
    {
        return $this->iscurrent;
    }

    public function setCurrent(bool $current): self
    {
        $this->iscurrent = $current;

        return $this;
    }

    public function getSimulationdatetime(): ?\DateTimeInterface
    {
        return $this->simulationdatetime;
    }

    public function setSimulationdatetime(\DateTimeInterface $simulationdate): self
    {
        $this->simulationdatetime = $simulationdate;

        return $this;
    }

    public function getNumberofpatient(): ?int
    {
        return $this->numberofpatient;
    }

    public function setNumberofpatient(int $numberofpatient): self
    {
        $this->numberofpatient = $numberofpatient;

        return $this;
    }

    public function getNumberofhumanresource(): ?int
    {
        return $this->numberofhumanresource;
    }

    public function setNumberofhumanresource(int $numberofhumanresource): self
    {
        $this->numberofhumanresource = $numberofhumanresource;

        return $this;
    }

    public function getNumberofmaterialresource(): ?int
    {
        return $this->numberofmaterialresource;
    }

    public function setNumberofmaterialresource(int $numberofmaterialresource): self
    {
        $this->numberofmaterialresource = $numberofmaterialresource;

        return $this;
    }
}