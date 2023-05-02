<?php

namespace App\Entity;

use App\Repository\WeatherTableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeatherTableRepository::class)]
class WeatherTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $retainerPercentage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRetainerPercentage(): ?int
    {
        return $this->retainerPercentage;
    }

    public function setRetainerPercentage(?int $retainerPercentage): self
    {
        $this->retainerPercentage = $retainerPercentage;

        return $this;
    }
}
