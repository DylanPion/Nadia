<?php

namespace App\Entity;

use App\Repository\AgreementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgreementRepository::class)]
class Agreement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Number = null;

    #[ORM\Column]
    private ?int $CashFund = null;

    #[ORM\OneToMany(mappedBy: 'agreement', targetEntity: CompanySheet::class)]
    private Collection $companySheet;

    #[ORM\Column(nullable: true)]
    private ?int $AmountRequestedForTheAgreement = null;

    public function __construct()
    {
        $this->companySheet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->Number;
    }

    public function setNumber(int $Number): self
    {
        $this->Number = $Number;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->Number; //  cette méthode permet de convertir l'objet Agreement en une chaîne de caractères qui représente la valeur de sa propriété Number.
    }

    public function getCashFund(): ?int
    {
        return $this->CashFund;
    }

    public function setCashFund(int $CashFund): self
    {
        $this->CashFund = $CashFund;

        return $this;
    }

    /**
     * @return Collection<int, CompanySheet>
     */
    public function getCompanySheet(): Collection
    {
        return $this->companySheet;
    }

    public function addCompanySheet(CompanySheet $companySheet): self
    {
        if (!$this->companySheet->contains($companySheet)) {
            $this->companySheet->add($companySheet);
            $companySheet->setAgreement($this);
        }

        return $this;
    }

    public function removeCompanySheet(CompanySheet $companySheet): self
    {
        if ($this->companySheet->removeElement($companySheet)) {
            // set the owning side to null (unless already changed)
            if ($companySheet->getAgreement() === $this) {
                $companySheet->setAgreement(null);
            }
        }

        return $this;
    }

    public function getAmountRequestedForTheAgreement(): ?int
    {
        return $this->AmountRequestedForTheAgreement;
    }

    public function setAmountRequestedForTheAgreement(?int $AmountRequestedForTheAgreement): self
    {
        $this->AmountRequestedForTheAgreement = $AmountRequestedForTheAgreement;

        return $this;
    }
}
