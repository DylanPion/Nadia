<?php

namespace App\Entity;

use App\Repository\BreakageDeductionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BreakageDeductionRepository::class)]
class BreakageDeduction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $BreakageDeduction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBreakageDeduction(): ?int
    {
        return $this->BreakageDeduction;
    }

    public function setBreakageDeduction(?int $BreakageDeduction): self
    {
        $this->BreakageDeduction = $BreakageDeduction;

        return $this;
    }
}
