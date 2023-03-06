<?php

namespace App\Controller;

use App\Repository\CompanySheetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TreasuryController extends AbstractController
{
    #[Route('/treasury', name: 'app_treasury')]
    public function index(CompanySheetRepository $companySheetRepository): Response
    {

        // On créer un tableau qui aura en donnée chaque valeur de la fonction pour un élément $i
        $resultsTotalAmountRequestedByAgreementNumber = [];
        $resultsTotalAmountPaidByAgreementNumber = [];
        $resultsTotalAmountRepaidToDatedByAgreementNumber = [];
        $resultsAmountsCommittedAndNotPaid = [];

        // Je commence à 1 et non 0 car il n'y a pas de convention représentant le n°0
        for ($i = 1; $i <= 6; $i++) {
            $resultsTotalAmountRequestedByAgreementNumber[$i] = $companySheetRepository->getTotalAmountRequestedByAgreementNumber($i);
            $resultsTotalAmountPaidByAgreementNumber[$i] = $companySheetRepository->getTotalAmountPaidByAgreementNumber($i);
            $resultsTotalAmountRepaidToDatedByAgreementNumber[$i] = $companySheetRepository->getTotalAmountRepaidToDateByAgreementNumber($i);
        }

        for ($i = 1; $i <= 6; $i++) {
            $resultsAmountsCommittedAndNotPaid[] = $resultsTotalAmountRequestedByAgreementNumber[$i][0]['TotalAmountRequestedByAgreementNumber'] - $resultsTotalAmountPaidByAgreementNumber[$i][0]['TotalAmountPaidByAgreementNumber'];
        }

        return $this->render('treasury/index.html.twig', [
            'TotalAmountRequestedByAgreementNumber' => $resultsTotalAmountRequestedByAgreementNumber,
            'TotalAmountPaidByAgreementNumber' => $resultsTotalAmountPaidByAgreementNumber,
            'TotalAmountRepaidToDatedByAgreementNumber' => $resultsTotalAmountRepaidToDatedByAgreementNumber,
            'AmountCommittedAndNotPaid' => $resultsAmountsCommittedAndNotPaid
        ]);
    }
}
