<?php

namespace App\Entity;

use App\Repository\TotalAmountRepaidToDateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TotalAmountRepaidToDateRepository::class)]
class TotalAmountRepaidToDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $TotalAmountRepaidToDate = null;

    #[ORM\Column]
    private ?int $Payment = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\ManyToOne(inversedBy: 'totalAmountRepaidToDate')]
    private ?CompanySheet $CompanySheet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalAmountRepaidToDate(): ?int
    {
        return $this->TotalAmountRepaidToDate;
    }

    public function setTotalAmountRepaidToDate(int $TotalAmountRepaidToDate): self
    {
        $this->TotalAmountRepaidToDate = $TotalAmountRepaidToDate;

        return $this;
    }

    public function getPayment(): ?int
    {
        return $this->Payment;
    }

    public function setPayment(int $Payment): self
    {
        $this->Payment = $Payment;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
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
}
