<?php

namespace App\Entity;

use App\Repository\CompanySheetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanySheetRepository::class)]
class CompanySheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $CompanyName = null;

    #[ORM\ManyToOne(inversedBy: 'Company')]
    private ?Association $association = null;

    #[ORM\Column(length: 255)]
    private ?string $LoanStatus = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateOfCE = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $RepaymentStartDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $RepaymentEndDate = null;

    #[ORM\Column]
    private ?int $FniAmountRequested = null;

    #[ORM\Column]
    private ?int $FniAmountPaid = null;

    #[ORM\Column]
    private ?int $PaymentOne = null;

    #[ORM\Column(nullable: true)]
    private ?int $PaymentTwo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $PaymentOneDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $PaymentTwoDate = null;

    #[ORM\Column]
    private ?int $RemainsToBePaid = null;

    #[ORM\Column]
    private ?int $TotalAmountRepaidToDate = null;

    #[ORM\Column(length: 255)]
    private ?string $ProjectLeaderName1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ProjectLeaderName2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ProjectLeaderName3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ProjectLeaderName4 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->CompanyName;
    }

    public function setCompanyName(string $CompanyName): self
    {
        $this->CompanyName = $CompanyName;

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

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

    public function getDateOfCE(): ?\DateTimeInterface
    {
        return $this->DateOfCE;
    }

    public function setDateOfCE(\DateTimeInterface $DateOfCE): self
    {
        $this->DateOfCE = $DateOfCE;

        return $this;
    }

    public function getRepaymentStartDate(): ?\DateTimeInterface
    {
        return $this->RepaymentStartDate;
    }

    public function setRepaymentStartDate(\DateTimeInterface $RepaymentStartDate): self
    {
        $this->RepaymentStartDate = $RepaymentStartDate;

        return $this;
    }

    public function getRepaymentEndDate(): ?\DateTimeInterface
    {
        return $this->RepaymentEndDate;
    }

    public function setRepaymentEndDate(?\DateTimeInterface $RepaymentEndDate): self
    {
        $this->RepaymentEndDate = $RepaymentEndDate;

        return $this;
    }

    public function getFniAmountRequested(): ?int
    {
        return $this->FniAmountRequested;
    }

    public function setFniAmountRequested(int $FniAmountRequested): self
    {
        $this->FniAmountRequested = $FniAmountRequested;

        return $this;
    }

    public function getFniAmountPaid(): ?int
    {
        return $this->FniAmountPaid;
    }

    public function setFniAmountPaid(int $FniAmountPaid): self
    {
        $this->FniAmountPaid = $FniAmountPaid;

        return $this;
    }

    public function getPaymentOne(): ?int
    {
        return $this->PaymentOne;
    }

    public function setPaymentOne(int $PaymentOne): self
    {
        $this->PaymentOne = $PaymentOne;

        return $this;
    }

    public function getPaymentTwo(): ?int
    {
        return $this->PaymentTwo;
    }

    public function setPaymentTwo(?int $PaymentTwo): self
    {
        $this->PaymentTwo = $PaymentTwo;

        return $this;
    }

    public function getPaymentOneDate(): ?\DateTimeInterface
    {
        return $this->PaymentOneDate;
    }

    public function setPaymentOneDate(\DateTimeInterface $PaymentOneDate): self
    {
        $this->PaymentOneDate = $PaymentOneDate;

        return $this;
    }

    public function getPaymentTwoDate(): ?\DateTimeInterface
    {
        return $this->PaymentTwoDate;
    }

    public function setPaymentTwoDate(?\DateTimeInterface $PaymentTwoDate): self
    {
        $this->PaymentTwoDate = $PaymentTwoDate;

        return $this;
    }

    public function getRemainsToBePaid(): ?int
    {
        return $this->RemainsToBePaid;
    }

    public function setRemainsToBePaid(int $RemainsToBePaid): self
    {
        $this->RemainsToBePaid = $RemainsToBePaid;

        return $this;
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

    public function getProjectLeaderName1(): ?string
    {
        return $this->ProjectLeaderName1;
    }

    public function setProjectLeaderName1(string $ProjectLeaderName1): self
    {
        $this->ProjectLeaderName1 = $ProjectLeaderName1;

        return $this;
    }

    public function getProjectLeaderName2(): ?string
    {
        return $this->ProjectLeaderName2;
    }

    public function setProjectLeaderName2(?string $ProjectLeaderName2): self
    {
        $this->ProjectLeaderName2 = $ProjectLeaderName2;

        return $this;
    }

    public function getProjectLeaderName3(): ?string
    {
        return $this->ProjectLeaderName3;
    }

    public function setProjectLeaderName3(?string $ProjectLeaderName3): self
    {
        $this->ProjectLeaderName3 = $ProjectLeaderName3;

        return $this;
    }

    public function getProjectLeaderName4(): ?string
    {
        return $this->ProjectLeaderName4;
    }

    public function setProjectLeaderName4(?string $ProjectLeaderName4): self
    {
        $this->ProjectLeaderName4 = $ProjectLeaderName4;

        return $this;
    }
}
