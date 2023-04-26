<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $damageHistory = null;

    #[ORM\Column(nullable: true)]
    private ?int $account2748 = null;

    #[ORM\Column(nullable: true)]
    private ?int $account4671 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $year = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalAmountFniPaid = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalAmountRepaidToDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDamageHistory(): ?int
    {
        return $this->damageHistory;
    }

    public function setDamageHistory(?int $damageHistory): self
    {
        $this->damageHistory = $damageHistory;

        return $this;
    }

    public function getAccount2748(): ?int
    {
        return $this->account2748;
    }

    public function setAccount2748(?int $account2748): self
    {
        $this->account2748 = $account2748;

        return $this;
    }

    public function getAccount4671(): ?int
    {
        return $this->account4671;
    }

    public function setAccount4671(?int $account4671): self
    {
        $this->account4671 = $account4671;

        return $this;
    }

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(?\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getTotalAmountFniPaid(): ?int
    {
        return $this->totalAmountFniPaid;
    }

    public function setTotalAmountFniPaid(?int $totalAmountFniPaid): self
    {
        $this->totalAmountFniPaid = $totalAmountFniPaid;

        return $this;
    }

    public function getTotalAmountRepaidToDate(): ?int
    {
        return $this->totalAmountRepaidToDate;
    }

    public function setTotalAmountRepaidToDate(?int $totalAmountRepaidToDate): self
    {
        $this->totalAmountRepaidToDate = $totalAmountRepaidToDate;

        return $this;
    }
}
