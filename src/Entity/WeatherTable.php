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
    private ?int $accountingProvision5 = null;

    #[ORM\Column(nullable: true)]
    private ?int $accountingProvision4 = null;

    #[ORM\Column(nullable: true)]
    private ?int $accountingProvision3 = null;

    #[ORM\Column(nullable: true)]
    private ?int $accountingProvision2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $accountingProvision1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $accountingProvision0 = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountingProvision5(): ?int
    {
        return $this->accountingProvision5;
    }

    public function setAccountingProvision5(?int $accountingProvision5): self
    {
        $this->accountingProvision5 = $accountingProvision5;

        return $this;
    }

    public function getAccountingProvision4(): ?int
    {
        return $this->accountingProvision4;
    }

    public function setAccountingProvision4(?int $accountingProvision4): self
    {
        $this->accountingProvision4 = $accountingProvision4;

        return $this;
    }

    public function getAccountingProvision3(): ?int
    {
        return $this->accountingProvision3;
    }

    public function setAccountingProvision3(?int $accountingProvision3): self
    {
        $this->accountingProvision3 = $accountingProvision3;

        return $this;
    }

    public function getAccountingProvision2(): ?int
    {
        return $this->accountingProvision2;
    }

    public function setAccountingProvision2(?int $accountingProvision2): self
    {
        $this->accountingProvision2 = $accountingProvision2;

        return $this;
    }

    public function getAccountingProvision1(): ?int
    {
        return $this->accountingProvision1;
    }

    public function setAccountingProvision1(?int $accountingProvision1): self
    {
        $this->accountingProvision1 = $accountingProvision1;

        return $this;
    }

    public function getAccountingProvision0(): ?int
    {
        return $this->accountingProvision0;
    }

    public function setAccountingProvision0(?int $accountingProvision0): self
    {
        $this->accountingProvision0 = $accountingProvision0;

        return $this;
    }
}
