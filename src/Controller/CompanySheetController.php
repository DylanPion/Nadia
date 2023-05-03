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
        $form = $this->createForm(CompanySheetType::class, null, [
            'data_class' => CompanySheet::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companySheet = $form->getData();
            // La création d'un TotalAmountRepaidToDate en relation avec une CompanySheet est faites pour initialisé l'échancier à 0 à la date d'aujourd'hui puis le lier à la Société       
            $totalAmountRepaidToDate = new TotalAmountRepaidToDate();
            $totalAmountRepaidToDate->setPayment(0)
                ->setDate(new DateTime())
                ->setCompanySheet($companySheet);
            $em->persist($totalAmountRepaidToDate);
            $em->persist($companySheet);
            $em->flush();

            $id = $companySheet->getId();
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

        return $this->render('companySheet/createReceivedAmount.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Modification d'un paiement reçu pour le Total Remboursé
    #[Route(
        '/companysheet/account/edit/{id}',
        name: 'app_companysheet_account_edit',
        requirements: ['id' => '\d+']
    )]
    public function edit($id, Request $request, EntityManagerInterface $em, TotalAmountRepaidToDateRepository $totalAmoundRepaidToDateRepository)
    {
        $totalAmoundRepaidToDate = $totalAmoundRepaidToDateRepository->find($id);
        $form = $this->createForm(TotalAmoundRepaidToDateType::class, $totalAmoundRepaidToDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_companysheet_display', ['id' => $totalAmoundRepaidToDate->getCompanySheet()->getId()]);
        }

        return $this->render('companySheet/editReceivedAmount.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Affichage d'une fiche société 
    #[Route('/companysheet/{id}', name: 'app_companysheet_display', requirements: ['page' => '\d+'])]
    public function app_companysheet_display($id, CompanySheetRepository $companySheetRepository, CompanySheet $companySheet, TotalAmountRepaidToDateRepository $totalAmountRepaidToDateRepository, EntityManagerInterface $em, WeatherRepository $weatherRepository): Response
    {
        // Récupération de la liste des project leader dans un tableau
        $projectLeaderList = $companySheet->getProjectLeaders();
        $projectLeaderNameList = [];
        foreach ($projectLeaderList as $projectLeaderName) {
            $projectLeaderNameList[] = $projectLeaderName->getName();
        }

        $FniAmountRequested = ($companySheet->getPaymentOne() + $companySheet->getPaymentTwo());
        $totalPaymentReceived = $totalAmountRepaidToDateRepository->getTotalPaymentReceivedByCompany($id);
        $totalAmountRepaid =  $FniAmountRequested - $totalPaymentReceived;

        // Récupérer l'entité CompanySheet
        $companySheet = $em->getRepository(CompanySheet::class)->find($id);

        if (!$companySheet) {
            throw $this->createNotFoundException('Aucune entreprise trouvée pour cet identifiant : ' . $id);
        }

        // Affecter la valeur de la variable `totalAmountRepaid` à `remainsToBeReceived`
        $companySheet->setRemainsToBeReceived($totalAmountRepaid);

        //Sauvegarde la donnée du montant total de la Casse
        $totalAmountOfDamageByCompany = $companySheet->setTotalAmountOfDamage($weatherRepository->getTotalamountOfDamageByCompany($id));
        $em->persist($totalAmountOfDamageByCompany);
        $em->flush();

        // Sauvegarde la donnée du montant Total de la Casse
        $totalAmountOfAccountingByCompany = $companySheet->setTotalAmountOfAccountingProvision($weatherRepository->getTotalamountOfAccountingProvision($id));
        $em->persist($totalAmountOfAccountingByCompany);
        $em->flush();

        // Sauvegarde la valeur du Reste à Reçevoir si il change dû à un nouveau montant reçu 
        $test = $companySheetRepository->find($id);
        $remainsToBeReceived = $companySheet->getFNIAmountRequested() - $totalAmountRepaidToDateRepository->getTotalPaymentReceivedByCompany($id);
        $test->setRemainsToBeReceived($remainsToBeReceived);

        $em->flush();

        return $this->render('companySheet/displayCompanySheet.html.twig', [
            'company' => $companySheetRepository->find($id),
            'projectleadername' => $projectLeaderNameList,
            'associationName' => $companySheetRepository->find($id)->getAssociation()->getName(),
            'totalAmountRepaid' => $totalAmountRepaidToDateRepository->getTotalAmountRepaidToDateById($id),
            'totalPaymentReceived' => $totalAmountRepaidToDateRepository->getTotalPaymentReceivedByCompany($id),
            'weather' => $weatherRepository->findBy(array('CompanySheet' => $id)),
            'totalAmountOfDamage' => $weatherRepository->getTotalamountOfDamageByCompany($id),
            'totalAmountOfAccountingProvision' => $weatherRepository->getTotalamountOfAccountingProvision($id)
        ]);
    }
}
