<?php

namespace App\Controller;

use App\Entity\Agreement;
use App\Form\AgreementType;
use App\Form\WeatherTableType;
use App\Entity\BreakageDeduction;
use App\Form\BreakageDeductionType;
use App\Repository\AgreementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanySheetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BreakageDeductionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TreasuryController extends AbstractController
{
    // Création d'une nouvelle Convention.
    #[Route('/treasury/create', name: 'app_treasury_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AgreementType::class, null, [
            'data_class' => Agreement::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agreement = $form->getData();
            $em->persist($agreement);
            $em->flush();
            return $this->redirectToRoute('app_treasury_agreementList');
        }

        return $this->render('treasury/createAgreement.html.twig', [
            'formView' => $form->createView(),
        ]);
    }


    // Affichage des convention
    #[Route('/treasury/agreementList', name: 'app_treasury_agreementList')]
    public function agreementCard(CompanySheetRepository $companySheetRepository, AgreementRepository $agreementRepository, BreakageDeductionRepository $breakageDeductionRepository)
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
            $totalAmountRequestedByAgreement[$value] = $companySheetRepository->getTotalAmountRequestedByAgreement($value);

            // Utilise la fonction du Repository pour calculer le Total FNI versé par convention
            $totalAmountFNIPaidByAgreement[$value] = $companySheetRepository->getTotalAmountFNIPaidByAgreement($value);

            // Utilise la fonction du Repository pour calculer le Total des Casses
            $totalAmountOfDamageByAgreement[$value] = $companySheetRepository->getTotalAmountOfCaseByAgreement($value);

            // Utilise la fonction du Repository pour calculer le Total des Provisions
            $totalAmountOfAccountingProvision[$value] = $companySheetRepository->getTotalAmountOfAccountingProvisionByAgreement($value);


            // Utilise la fonction du Repository pour calculer le Total des Remboursement reçus par convetion 
            $totalAmountRepaidByAgreement[$value] =
                $companySheetRepository->getTotalAmountRepaidByAgreement($value);

            // Calcul le Montant engagé et non versé 
            $amountsCommittedAndNotPaid[$value] = $totalAmountRequestedByAgreement[$value][0]['TotalAmountRequestedByAgreement'] - $totalAmountFNIPaidByAgreement[$value][0]["TotalAmountFNIPaidByAgreement"];
        };

        // Récupération de la seule instance de BreakageDeduction
        $breakageDeduction = $breakageDeductionRepository->findAll()[0] ?? null; // Permet de Récupérer la première ligne de la table 

        // Si aucune instance n'existe, redirection vers une page pour en créer une
        if ($breakageDeduction === null) {
            return $this->redirectToRoute('app_create_breakage_deduction');
        }

        return $this->render('treasury/agreementList.html.twig', [
            'agreementNumber' => $agreementNumber,
            'agreement' => $agreement,
            'TotalAmountRequestedByAgreement' => $totalAmountRequestedByAgreement,
            'TotalAmountFNIPaidByAgreement' => $totalAmountFNIPaidByAgreement,
            'TotalAmountRepaidByAgreement' => $totalAmountRepaidByAgreement,
            'TotalAmountOfDamageByAgreement' => $totalAmountOfDamageByAgreement,
            'TotalAmountOfAccountingProvision' => $totalAmountOfAccountingProvision,
            'AmountCommittedAndNotPaid' => $amountsCommittedAndNotPaid,
            'idValue' => $idValue, // cela nous permettra de faire une boucle avec les valeurs de idValue pour afficher nos données
            'breakageDeduction' => $breakageDeduction,
        ]);
    }

    #[Route('/treasury/createBreakageDeduction', name: 'app_create_breakage_deduction')]
    public function createBreakageDeduction(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(BreakageDeductionType::class, null, ['data_class' => BreakageDeduction::class]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('app_treasury_agreementList');
        }

        return $this->render('treasury/createBrekageDeduction.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    #[Route('/treasury/editBreakageDeduction', name: 'app_edit_breakage_deduction')]
    public function editBreakageDeduction(EntityManagerInterface $em, Request $request, BreakageDeductionRepository $breakageDeductionRepository)
    {
        $breakageDeduction = $breakageDeductionRepository->findAll()[0];
        $form = $this->createForm(BreakageDeductionType::class, $breakageDeduction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_treasury_agreementList');
        }

        return $this->render('treasury/editBrekageDeduction.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
