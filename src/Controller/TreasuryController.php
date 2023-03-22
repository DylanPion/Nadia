<?php

namespace App\Controller;

use App\Entity\Agreement;
use App\Entity\Association;
use Doctrine\ORM\Mapping\Entity;
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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
        $agreement = [];
        $TotalAmountRequestedByAgreement = []; // Montant Total Engagés Par Convention
        $TotalAmountPaidByAgreement = []; // Montant Total Versé Par Conventio
        $resultsAmountsCommittedAndNotPaid = [];

        foreach ($idValue as $valeur) { // On remplit nos Tableau pour chaque élément ayant pour id la valeur de idValue.

            $agreement[$valeur] = $agreementRepository->find($valeur);
            $TotalAmountRequestedByAgreement[$valeur] = $companySheetRepository->getTotalAmountRequestedByAgreement($valeur);

            $TotalAmountPaidByAgreement[$valeur] = $companySheetRepository->getTotalAmountPaidByAgreement($valeur);

            $resultsAmountsCommittedAndNotPaid[$valeur] = $TotalAmountRequestedByAgreement[$valeur][0]['TotalAmountRequestedByAgreement'] - $TotalAmountPaidByAgreement[$valeur][0]['TotalAmountPaidByAgreement'];
        }

        return $this->render('treasury/agreementList.html.twig', [
            'agreement' => $agreement,
            'TotalAmountRequestedByAgreement' => $TotalAmountRequestedByAgreement,
            'TotalAmountPaidByAgreement' => $TotalAmountPaidByAgreement,
            'AmountCommittedAndNotPaid' => $resultsAmountsCommittedAndNotPaid,
            'idValue' => $idValue // cela nous permettra de faire une boucle avec les valeurs de idValue pour afficher nos données
        ]);
    }

    // Affichage de l'historique du Total Remboursé à ce jour
    #[Route('/companysheet/account/{id}', name: 'app_companysheet_account', requirements: ['id' => '\d+'])]
    public function account($id, TotalAmoundRepaidToDateType $totalAmoundRepaidToDateType, Request $request, EntityManagerInterface $em, TotalAmountRepaidToDateRepository $totalAmountRepaidToDateRepository, CompanySheetRepository $companySheetRepository)

    {
        $test = $companySheetRepository->find($id);
        $form = $this->createForm(TotalAmoundRepaidToDateType::class, null, [
            'data_class' => TotalAmountRepaidToDate::class,
        ]);

        $form->add('companySheet', HiddenType::class, [ // Le champ companySheet doit reçevoir un objet de Type "companyheet"
            'data' => $test, // passe comme valeur à companySheet la valeur de id
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
