<?php

namespace App\Controller;

use App\Entity\Agreement;
use App\Form\AgreementCreateType;
use App\Form\AgreementListType;
use App\Repository\AgreementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanySheetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TreasuryController extends AbstractController
{

    // Création d'une nouvelle Convention.
    #[Route('/treasury/create', name: 'app_treasury_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AgreementCreateType::class, null, [
            'data_class' => Agreement::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agreement = $form->getData();
            $em->persist($agreement);
            $em->flush();
            return $this->redirectToRoute('app_association');
        }

        return $this->render('treasury/createTreasury.html.twig', [
            'formView' => $form->createView(),
        ]);
    }


    #[Route('/treasury/agreementList', name: 'app_treasury_agreementList')]
    public function agreementList(Request $request): Response
    {
        $form = $this->createForm(AgreementListType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = strval($form->get('Number')->getData()); // strval convertit en une chaine de caractère
            return $this->redirectToRoute('app_treasury_agreementCard', ['id' => $id]);
        }

        return $this->render('treasury/agreementList.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/treasury/{id}', name: 'app_treasury_agreementCard')]
    public function agreementCard($id, CompanySheetRepository $companySheetRepository, AgreementRepository $agreementRepository)
    {

        // On créer un tableau qui aura en donnée chaque valeur de la fonction pour un élément $i
        $resultsTotalAmountRequestedByAgreement = [];
        $resultsTotalAmountPaidByAgreement = [];
        $resultsTotalAmountRepaidToDatedByAgreement = [];
        $resultsAmountsCommittedAndNotPaid = [];

        // Je commence à 1 et non 0 car il n'y a pas de convention représentant le n°0
        for ($i = 1; $i <= 6; $i++) {
            $resultsTotalAmountRequestedByAgreement[$i] = $companySheetRepository->getTotalAmountRequestedByAgreement($i);
            $resultsTotalAmountPaidByAgreement[$i] = $companySheetRepository->getTotalAmountPaidByAgreement($i);
            $resultsTotalAmountRepaidToDatedByAgreement[$i] = $companySheetRepository->getTotalAmountRepaidToDateByAgreement($i);
        }

        for ($i = 1; $i <= 6; $i++) {
            $resultsAmountsCommittedAndNotPaid[] = $resultsTotalAmountRequestedByAgreement[$i][0]['TotalAmountRequestedByAgreement'] - $resultsTotalAmountPaidByAgreement[$i][0]['TotalAmountPaidByAgreement'];
        }

        return $this->render('treasury/agreementNumber.html.twig', [
            'id' => $id,
            'TotalAmountRequestedByAgreement' => $resultsTotalAmountRequestedByAgreement,
            'TotalAmountPaidByAgreement' => $resultsTotalAmountPaidByAgreement,
            'TotalAmountRepaidToDatedByAgreement' => $resultsTotalAmountRepaidToDatedByAgreement,
            'AmountCommittedAndNotPaid' => $resultsAmountsCommittedAndNotPaid,
            'Agreement' => $agreementRepository->find($id)
        ]);
    }
}
