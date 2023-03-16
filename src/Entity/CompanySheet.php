<?php

namespace App\Entity;

use App\Repository\CompanySheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(inversedBy: 'companySheet')]
    private ?Agreement $Agreement = null;

    #[ORM\OneToMany(mappedBy: 'CompanySheet', targetEntity: ProjectLeader::class)]
    private Collection $projectLeaders;

    #[ORM\OneToOne(mappedBy: 'CompanySheet', targetEntity: TotalAmountRepaidToDate::class)]
    private ?TotalAmountRepaidToDate $TotalAmountRepaidToDate = null;

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
    private int $RemainsToBePaid = 0;

    #[ORM\Column]
    private int $remainsToBeReceived = 0;

    #[ORM\Column]
    private ?int $TotalFniAmountRequested = 0;

    #[ORM\Column]
    private ?int $TotalFniAmountPaid = 0;

    public function __construct()
    {
        $this->projectLeaders = new ArrayCollection();
    }

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

    public function getAgreement(): ?Agreement
    {
        return $this->Agreement;
    }

    public function setAgreement(?Agreement $Agreement): self
    {
        $this->Agreement = $Agreement;

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

    public function getRemainsToBePaid(): int
    {
        return $this->RemainsToBePaid;
    }

    public function setRemainsToBePaid($RemainsToBePaid): self
    {
        $this->FniAmountRequested - $this->PaymentOne - $this->PaymentTwo = $RemainsToBePaid;

        return $this;
    }

    public function getRemainsToBeReceived(): int
    {
        return $this->remainsToBeReceived;
    }

    public function setRemainsToBeReceived($remainsToBeReceived): self
    {
        $this->FniAmountRequested - $this->TotalAmountRepaidToDate = $remainsToBeReceived;
        return $this;
    }

    public function getTotalFniAmountRequested(): ?int
    {
        return $this->TotalFniAmountRequested;
    }

    public function setTotalFniAmountRequested(int $TotalFniAmountRequested): self
    {
        $this->TotalFniAmountRequested = $TotalFniAmountRequested;

        return $this;
    }

    public function getTotalFniAmountPaid(): ?int
    {
        return $this->TotalFniAmountPaid;
    }

    public function setTotalFniAmountPaid(int $TotalFniAmountPaid): self
    {
        $this->TotalFniAmountPaid = $TotalFniAmountPaid;

        return $this;
    }

    /**
     * @return Collection<int, ProjectLeader>
     */
    public function getProjectLeaders(): Collection
    {
        return $this->projectLeaders;
    }

    public function addProjectLeader(ProjectLeader $projectLeader): self
    {
        if (!$this->projectLeaders->contains($projectLeader)) {
            $this->projectLeaders->add($projectLeader);
            $projectLeader->setCompanySheet($this);
        }

        return $this;
    }

    public function removeProjectLeader(ProjectLeader $projectLeader): self
    {
        if ($this->projectLeaders->removeElement($projectLeader)) {
            // set the owning side to null (unless already changed)
            if ($projectLeader->getCompanySheet() === $this) {
                $projectLeader->setCompanySheet(null);
            }
        }

        return $this;
    }
}
