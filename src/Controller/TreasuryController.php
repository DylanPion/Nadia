<?php

namespace App\Controller;

use App\Entity\Agreement;
use App\Form\AgreementType;
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

            // Utilise la fonction du Repository pour calculer le Total des Remboursement reçus par convetion 
            $TotalAmountRepaidByAgreement[$value] =
                $companySheetRepository->getTotalAmountRepaidByAgreement($value);

            // Calcul le Montant engagé et non versé 
            $AmountsCommittedAndNotPaid[$value] = $TotalAmountRequestedByAgreement[$value][0]['TotalAmountRequestedByAgreement'] - $TotalAmountFNIPaidByAgreement[$value][0]["TotalAmountFNIPaidByAgreement"];
        }
        return $this->render('treasury/agreementList.html.twig', [
            'agreementNumber' => $agreementNumber,
            'agreement' => $agreement,
            'TotalAmountRequestedByAgreement' => $TotalAmountRequestedByAgreement,
            'TotalAmountFNIPaidByAgreement' => $TotalAmountFNIPaidByAgreement,
            'TotalAmountRepaidByAgreement' => $TotalAmountRepaidByAgreement,
            'AmountCommittedAndNotPaid' => $AmountsCommittedAndNotPaid,
            'idValue' => $idValue // cela nous permettra de faire une boucle avec les valeurs de idValue pour afficher nos données
        ]);
    }
}
