<?php

namespace App\Controller;

use App\Entity\Agreement;
use App\Entity\CompanySheet;
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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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


    // Affichage des convention
    #[Route('/treasury/agreementList', name: 'app_treasury_agreementList')]
    public function agreementCard(CompanySheetRepository $companySheetRepository, AgreementRepository $agreementRepository)
    {
        // idValueAgreement est une fonction du Agreement Repository. C'est un tableau contenant la liste des Id de la table Agreement 
        $idValue = $agreementRepository->idValueAgreement();
        // On créer un tableau qui aura en donnée chaque valeur de la fonction pour un élément $i

        foreach ($idValue as $value) { // On remplit nos Tableau pour chaque élément ayant pour id la valeur de idValue.

            // Récupère l'Agreement pour la valeur de Value 
            $agreement[$value] = $agreementRepository->find($value);

            // Récupère l'id de l'Agreement pour la valeur de Value 
            $agreementNumber[$value] = $agreementRepository->find($value)->getNumber();

            // Utilise la fonction du Repository pour calculer le Total FNI engagé par convention
            $TotalAmountRequestedByAgreement[$value] = $companySheetRepository->getTotalAmountRequestedByAgreement($value);

            // Utilise la fonction du Repository pour calculer le Total FNI versé par convention
            $TotalAmountFNIPaidByAgreement[$value] = $companySheetRepository->getTotalAmountFNIPaidByAgreement($value);

            // Calcul le Montant engagé et non versé 
            $AmountsCommittedAndNotPaid[$value] = $TotalAmountRequestedByAgreement[$value][0]['TotalAmountRequestedByAgreement'] - $TotalAmountFNIPaidByAgreement[$value][0]["TotalAmountFNIPaidByAgreement"];
        }
        return $this->render('treasury/agreementList.html.twig', [
            'agreementNumber' => $agreementNumber,
            'agreement' => $agreement,
            'TotalAmountRequestedByAgreement' => $TotalAmountRequestedByAgreement,
            'TotalAmountFNIPaidByAgreement' => $TotalAmountFNIPaidByAgreement,
            'AmountCommittedAndNotPaid' => $AmountsCommittedAndNotPaid,
            'idValue' => $idValue // cela nous permettra de faire une boucle avec les valeurs de idValue pour afficher nos données
        ]);
    }

    // Création d'un nouveau paiement reçu pour le Total Remboursé
    #[Route('/companysheet/account/create{id}', name: 'app_companysheet_account_create', requirements: ['id' => '\d+'])]
    public function account($id, Request $request, EntityManagerInterface $em, CompanySheetRepository $companySheetRepository, TotalAmoundRepaidToDateType $totalAmoundRepaidToDateType)
    {
        $totalAmoundRepaidToDateType = new TotalAmountRepaidToDate();
        $form = $this->createForm(TotalAmoundRepaidToDateType::class, $totalAmoundRepaidToDateType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $CS = $companySheetRepository->find($id);
            $data->setCompanySheet($CS);

            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('app_companysheet_display', ['id' => $id]);
        }

        return $this->render('treasury/createReceivedAmount.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Modification d'un paiement reçu pour le Total Remboursé
    #[Route('/companysheet/account/edit/{id}', name: 'app_companysheet_account_edit', requirements: ['id' => '\d+'])]
    public function edit($id, Request $request, EntityManagerInterface $em, TotalAmountRepaidToDateRepository $totalAmoundRepaidToDateRepository)
    {
        $totalAmoundRepaidToDate = $totalAmoundRepaidToDateRepository->find($id);
        $form = $this->createForm(TotalAmoundRepaidToDateType::class, $totalAmoundRepaidToDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_companysheet_display', ['id' => $totalAmoundRepaidToDate->getCompanySheet()->getId()]);
        }

        return $this->render('treasury/editReceivedAmount.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
