<?php

namespace App\Entity;

use App\Repository\ProjectLeaderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectLeaderRepository::class)]
class ProjectLeader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectLeaders')]
    private ?CompanySheet $CompanySheet = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Name = null;

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

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }
}
