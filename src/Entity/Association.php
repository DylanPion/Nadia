<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: CompanySheet::class)]
    private Collection $Company;

    public function __construct()
    {
        $this->Company = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, CompanySheet>
     */
    public function getCompany(): Collection
    {
        return $this->Company;
    }

    public function addCompany(CompanySheet $company): self
    {
        if (!$this->Company->contains($company)) {
            $this->Company->add($company);
            $company->setAssociation($this);
        }

        return $this;
    }

    public function removeCompany(CompanySheet $company): self
    {
        if ($this->Company->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getAssociation() === $this) {
                $company->setAssociation(null);
            }
        }

        return $this;
    }
}
