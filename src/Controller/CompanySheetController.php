<?php

namespace App\Controller;

use DateTime;
use App\Entity\CompanySheet;
use App\Form\CompanySheetType;
use App\Entity\TotalAmountRepaidToDate;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TotalAmoundRepaidToDateType;
use App\Repository\CompanySheetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TotalAmountRepaidToDateRepository;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanySheetController extends AbstractController
{
    // Création d'une fiche société + Liaison à un échéancier de remboursement (TotalAmountRepaidToDate)
    #[Route('/companysheet/create', name: 'app_companysheet_create')]
    public function app_companysheet_create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanySheetType::class, null, ['data_class' => CompanySheet::class]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companySheet = $form->getData();
            $totalAmountRepaidToDate = new TotalAmountRepaidToDate(); // Créer un Échéancier lier à la Société qui va être créer 
            $totalAmountRepaidToDate->setPayment(0)
                ->setDate(new DateTime()) // Le paiement et la date sont initialiser à 0 euros à la date d'aujourd'hui
                ->setCompanySheet($companySheet);
            $em->persist($totalAmountRepaidToDate);
            $em->persist($companySheet);
            $em->flush();

            $id = $companySheet->getId(); // Récupère l'id de la Société qui vient d'être créer pour faire une redirection
            return $this->redirectToRoute('app_companysheet_display', ['id' => $id]);
        }

        return $this->render('companySheet/createCompanySheet.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Modification d'une fiche société
    #[Route('/companysheet/edit/{id}', name: 'app_companysheet_edit', requirements: ['page' => '\d+'])]
    public function app_companysheet_edit($id, CompanySheet $companySheet, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanySheetType::class, $companySheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companySheet = $form->getData();
            $em->persist($companySheet);
            $em->flush();
            return $this->redirectToRoute('app_association');
        };

        return $this->render('companySheet/editCompanySheet.html.twig', [
            'companySheet' => $companySheet,
            'formView' => $form->createView()
        ]);
    }

    // Supression d'une fiche société
    #[Route('/companysheet/delete/{id}', name: 'app_companysheet_delete', requirements: ['page' => '\d+'])]
    public function app_companysheet_delete(CompanySheet $companySheet, EntityManagerInterface $em): Response
    {
        $em->remove($companySheet);
        $em->flush();
        return $this->redirectToRoute('app_association');
    }

    // Création d'un Montant Reçus pour le remboursement
    #[Route('/companysheet/createAccount/{id}', name: 'app_companysheet_createAccount', requirements: ['id' => '\d+'])]
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

        return $this->render('companySheet/createReceivedAmount.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Modification d'un paiement reçu pour le Total Remboursé
    #[Route('/companysheet/account/edit/{id}', name: 'app_companysheet_accountEdit', requirements: ['id' => '\d+'])]
    public function edit($id, Request $request, EntityManagerInterface $em, TotalAmountRepaidToDate $totalAmoundRepaidToDate)
    {
        $form = $this->createForm(TotalAmoundRepaidToDateType::class, $totalAmoundRepaidToDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $totalAmoundRepaidToDate = $form->getData();
            $em->persist($totalAmoundRepaidToDate);
            $em->flush();

            return $this->redirectToRoute('app_companysheet_display', ['id' => $totalAmoundRepaidToDate->getCompanySheet()->getId()]);
        }

        return $this->render('companySheet/editReceivedAmount.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Affichage d'une fiche société 
    #[Route('/companysheet/{id}', name: 'app_companysheet_display', requirements: ['page' => '\d+'])]
    public function displayCompanySheet(
        int $id,
        CompanySheetRepository $companySheetRepository,
        TotalAmountRepaidToDateRepository $totalAmountRepaidToDateRepository,
        EntityManagerInterface $em,
        WeatherRepository $weatherRepository
    ): Response {
        // Récupérer l'entité CompanySheet
        $companySheet = $companySheetRepository->find($id);

        // Calculer le montant total de l'indemnisation
        $fniAmountPaid = $companySheet->getPaymentOne() + $companySheet->getPaymentTwo();
        $totalPaymentReceived = $totalAmountRepaidToDateRepository->getTotalPaymentReceivedByCompany($id);
        $totalAmountRepaid = $fniAmountPaid - $totalPaymentReceived;

        // Mettre à jour le montant restant à recevoir
        $companySheet->setRemainsToBeReceived($totalAmountRepaid);

        // Mettre à jour le montant total des dégâts
        $companySheet->setTotalAmountOfDamage($weatherRepository->getTotalamountOfDamageByCompany($id));

        // Mettre à jour le montant total de la provision comptable
        $companySheet->setTotalAmountOfAccountingProvision($weatherRepository->getTotalamountOfAccountingProvision($id));

        // Enregistrer les modifications
        $em->flush();

        // Préparer les données pour l'affichage
        $projectLeaderNameList = $companySheet->getProjectLeaders()->map(fn ($projectLeader) => $projectLeader->getName())->toArray();
        $remainsToBeReceived = $fniAmountPaid - $totalAmountRepaidToDateRepository->getTotalPaymentReceivedByCompany($id);

        return $this->render('companySheet/displayCompanySheet.html.twig', [
            'company' => $companySheet,
            'projectleadername' => $projectLeaderNameList,
            'associationName' => $companySheet->getAssociation()->getName(),
            'totalAmountRepaid' => $totalAmountRepaidToDateRepository->getTotalAmountRepaidToDateById($id),
            'totalPaymentReceived' => $totalPaymentReceived,
            'weather' => $weatherRepository->findBy(['CompanySheet' => $id]),
            'totalAmountOfDamage' => $companySheet->getTotalAmountOfDamage(),
            'totalAmountOfAccountingProvision' => $companySheet->getTotalAmountOfAccountingProvision(),
            'remainsToBeReceived' => $remainsToBeReceived,
        ]);
    }
}
