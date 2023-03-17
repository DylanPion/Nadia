<?php

namespace App\Service;

use App\Entity\CompanySheet;
use Doctrine\ORM\EntityManagerInterface;

class CalculService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculateRemainsToBePaid(int $companySheetId): float
    {
        $companySheet = $this->entityManager->getRepository(CompanySheet::class)->find($companySheetId);

        if (!$companySheet instanceof CompanySheet) {
            throw new \InvalidArgumentException("Company sheet not found for ID: $companySheetId");
        }

        $fniAmountRequested = $companySheet->getFniAmountRequested();
        $paymentOne = $companySheet->getPaymentOne();
        $paymentTwo = $companySheet->getPaymentTwo();

        $remainsToBePaid = $fniAmountRequested - $paymentOne - $paymentTwo;

        return $remainsToBePaid;
    }
}
