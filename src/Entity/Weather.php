<?php

namespace App\Entity;

use App\Repository\WeatherRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeatherRepository::class)]
class Weather
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'weather')]
    private ?CompanySheet $CompanySheet = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $weatherYear = null;

    #[ORM\Column(nullable: true)]
    private ?int $unpaidNumber = null;

    #[ORM\Column(nullable: true)]
    private ?int $assessmentScale = null;

    #[ORM\Column(nullable: true)]
    private ?int $retainerPercentage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $liquidationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bpiGuarantee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(nullable: true)]
    private ?int $amountOfDamage = null;

    #[ORM\Column(length: 255)]
    private ?string $LoanStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanySheet(): ?CompanySheet
    {
        return $this->CompanySheet;
    }

    public function setCompanySheet(?CompanySheet $CompanySheet): self
    {
        $this->CompanySheet = $CompanySheet;

        return $this;
    }

    public function getWeatherYear(): ?\DateTimeInterface
    {
        return $this->weatherYear;
    }

    public function setWeatherYear(?\DateTimeInterface $weatherYear): self
    {
        $this->weatherYear = $weatherYear;

        return $this;
    }

    public function getUnpaidNumber(): ?int
    {
        return $this->unpaidNumber;
    }

    public function setUnpaidNumber(?int $unpaidNumber): self
    {
        $this->unpaidNumber = $unpaidNumber;

        return $this;
    }

    public function getAssessmentScale(): ?int
    {
        return $this->assessmentScale;
    }

    public function setAssessmentScale(?int $assessmentScale): self
    {
        $this->assessmentScale = $assessmentScale;

        return $this;
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

    public function getLiquidationDate(): ?\DateTimeInterface
    {
        return $this->liquidationDate;
    }

    public function setLiquidationDate(?\DateTimeInterface $liquidationDate): self
    {
        $this->liquidationDate = $liquidationDate;

        return $this;
    }

    public function getBpiGuarantee(): ?string
    {
        return $this->bpiGuarantee;
    }

    public function setBpiGuarantee(?string $bpiGuarantee): self
    {
        $this->bpiGuarantee = $bpiGuarantee;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAmountOfDamage(): ?int
    {
        return $this->amountOfDamage;
    }

    public function setAmountOfDamage(?int $amountOfDamage): self
    {
        $this->amountOfDamage = $amountOfDamage;

        return $this;
    }

    public function getLoanStatus(): ?string
    {
        return $this->LoanStatus;
    }

    public function setLoanStatus(string $LoanStatus): self
    {
        $this->LoanStatus = $LoanStatus;

        return $this;
    }
}
