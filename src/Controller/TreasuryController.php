<?php

namespace App\Controller;

use App\Entity\Agreement;
use App\Form\AgreementListType;
use App\Form\AgreementCreateType;
use App\Entity\TotalAmountRepaidToDate;
use App\Repository\AgreementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TotalAmoundRepaidToDateType;
use App\Repository\CompanySheetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TotalAmountRepaidToDateRepository;
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

        return $this->render('treasury/createAgreement.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Affichage d'un formulaire redirigeant vers la convention choisi
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

    // Affichage d'une convention
    #[Route('/treasury/agreementList/{id}', name: 'app_treasury_agreementCard', requirements: ['id' => '\d+'])]
    public function agreementCard($id, CompanySheetRepository $companySheetRepository, AgreementRepository $agreementRepository, TotalAmountRepaidToDateRepository $totalAmountRepaidToDateRepository)
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
            $resultsTotalAmountRepaidToDatedByAgreement[$i] = $totalAmountRepaidToDateRepository->getTotalAmountRepaidToDateByAgreement($i);
        }

        for ($i = 1; $i <= 6; $i++) {
            $resultsAmountsCommittedAndNotPaid[] = $resultsTotalAmountRequestedByAgreement[$i][0]['TotalAmountRequestedByAgreement'] - $resultsTotalAmountPaidByAgreement[$i][0]['TotalAmountPaidByAgreement'];
        }

        return $this->render('treasury/agreementCard.html.twig', [
            'id' => $id,
            'TotalAmountRequestedByAgreement' => $resultsTotalAmountRequestedByAgreement,
            'TotalAmountPaidByAgreement' => $resultsTotalAmountPaidByAgreement,
            'TotalAmountRepaidToDatedByAgreement' => $resultsTotalAmountRepaidToDatedByAgreement,
            'AmountCommittedAndNotPaid' => $resultsAmountsCommittedAndNotPaid,
            'Agreement' => $agreementRepository->find($id)
        ]);
    }

    // Affichage de l'historique du Total Remboursé à ce jour
    #[Route('/companysheet/account/{id}', name: 'app_companysheet_account', requirements: ['id' => '\d+'])]
    public function account($id, TotalAmoundRepaidToDateType $totalAmoundRepaidToDateType, Request $request, EntityManagerInterface $em, TotalAmountRepaidToDateRepository $totalAmountRepaidToDateRepository)

    {
        $form = $this->createForm(TotalAmoundRepaidToDateType::class, null, [
            'data_class' => TotalAmountRepaidToDate::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $TotalAmountRepaidToDate = $form->getData();
            $em->persist($TotalAmountRepaidToDate);
            $em->flush();
            // return $this->redirectToRoute('app_association');
        }

        return $this->render(
            'treasury/account.html.twig',
            [
                'formView' => $form->createView(),
                'totalAmountRepaidToDate' => $totalAmountRepaidToDateRepository->getTotalAmountRepaidToDateById($id)
            ]
        );
    }

    // Modification Total Remboursé à ce jour
    #[Route('/companysheet/account/edit/{id}', name: 'app_account_edit', requirements: ['id' => '\d+'])]
    public function accountEdit($id, TotalAmountRepaidToDate $totalAmountRepaidToDate, Request $request, EntityManagerInterface $em, TotalAmoundRepaidToDateType $totalAmoundRepaidToDateType): Response
    {
        $form = $this->createForm(TotalAmoundRepaidToDateType::class, $totalAmountRepaidToDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $totalAmountRepaidToDate = $form->getData();
            $em->persist($totalAmountRepaidToDate);
            $em->flush();
            return $this->redirectToRoute('app_association');
        };

        return $this->render('treasury/editAccount.html.twig', [
            'companySheet' => $totalAmoundRepaidToDateType,
            'formView' => $form->createView()
        ]);
    }

    // Supression Total Remboursé à ce jour
    #[Route('companysheet/account/delete/{id}', name: 'app_account_delete', requirements: ['id' => '\d+'])]
    public function accountDelete(TotalAmountRepaidToDate $totalAmountRepaidToDate, EntityManagerInterface $em): Response
    {
        $em->remove($totalAmountRepaidToDate);
        $em->flush();
        return $this->redirectToRoute('app_association');
    }
}
