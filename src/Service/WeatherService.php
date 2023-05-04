<?php

namespace App\Service;

use App\Entity\CompanySheet;
use App\Repository\CompanySheetRepository;
use Symfony\Component\Form\FormInterface;

class WeatherService
{
    private $companySheetRepository;

    public function __construct(CompanySheetRepository $companySheetRepository)
    {
        $this->companySheetRepository = $companySheetRepository;
    }

    public function calculateAmountOfAccountingProvision(FormInterface $form, CompanySheet $companySheet): float
    {
        $dateOfTheLastDayOfTheYear = $form->get('DateOfTheLastDayOfTheYear')->getData();
        $retainerPercentage = ($form->get("retainerPercentage")->getData()) / 100;
        $company = $this->companySheetRepository->find($companySheet);
        $dateOfCe = $company->getDateOfCE();
        $remainToBeReceved = $company->getRemainsToBeReceived();
        $interval = $dateOfTheLastDayOfTheYear->diff($dateOfCe);
        $diffInMonths = $interval->y * 12 + $interval->m;
        if ($diffInMonths > 6) {
            $amountOfAccountingProvision = ($remainToBeReceved * $retainerPercentage) * 0.30;
        } else {
            $amountOfAccountingProvision = $remainToBeReceved;
        }
        return $amountOfAccountingProvision;
    }
}
