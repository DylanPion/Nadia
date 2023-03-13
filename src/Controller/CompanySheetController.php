<?php

namespace App\Controller;

use App\Entity\CompanySheet;
use App\Entity\ProjectLeader;
use App\Form\CompanySheetType;
use App\Form\ProjectLeaderType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanySheetRepository;
use App\Repository\TotalAmountRepaidToDateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanySheetController extends AbstractController
{
    // Création d'une fiche société
    #[Route('/companysheet/create', name: 'app_companysheet_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanySheetType::class, null, [
            'data_class' => CompanySheet::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companySheet = $form->getData();
            $em->persist($companySheet);
            $em->flush();
            return $this->redirectToRoute('app_association');
        }

        return $this->render('companySheet/createCompanySheet.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Modification d'une fiche société
    #[Route('/companysheet/{id}/edit', name: 'app_companysheet_edit')]
    public function edit($id, CompanySheet $companySheet, Request $request, EntityManagerInterface $em): Response
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
    #[Route('/companysheet/{id}/delete', name: 'app_companysheet_delete')]
    public function delete(CompanySheet $companySheet, EntityManagerInterface $em): Response
    {
        $em->remove($companySheet);
        $em->flush();
        return $this->redirectToRoute('app_association');
    }

    // Affichage du détail d'une fiche société
    #[Route('/companysheet/{id}/show', name: 'app_companysheet_show')]
    public function show($id, CompanySheetRepository $companySheetRepository, CompanySheet $companySheet): Response
    {
        // Récupération de la liste des project leader 
        $projectLeaderList = $companySheet->getProjectLeaders(); // Pas besoin de spécifier l'id de la fiche société car la méthode getProjectLeader retourne déjà tout les projectleader assciés à l'instance companysheet.
        $projectLeaderNameList = [];
        foreach ($projectLeaderList as $projectLeaderName) {
            $projectLeaderNameList[] = $projectLeaderName->getName();
        }

        return $this->render('companySheet/showCompanySheet.html.twig', [
            'company' => $companySheetRepository->find($id),
            'projectleadername' => $projectLeaderNameList
        ]);
    }

    // Affichage de l'historique du Total Remboursé à ce jour
    #[Route('/companysheet/{id}/account', name: 'app_companysheet_account')]
    public function account($id, CompanySheetRepository $companySheetRepository, TotalAmountRepaidToDateRepository $totalAmountRepaidToDateRepository): Response
    {
        return $this->render('companySheet/account.html.twig', [
            'company' => $companySheetRepository->find($id),
            'TotalAmountRepaidToDate' => $totalAmountRepaidToDateRepository->find($id)
        ]);
    }

    #[Route('companysheet/project-leader', name: 'app_companysheet_projectleader')]
    public function createProjectLeader(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProjectLeaderType::class, null, [
            'data_class' => ProjectLeader::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectleader = $form->getData();
            $em->persist($projectleader);
            $em->flush();
            return $this->redirectToRoute('app_association');
        }
        return $this->render(
            'companySheet/projectleader.html.twig',
            [
                'formView' => $form->createView(),
            ]
        );
    }
}
