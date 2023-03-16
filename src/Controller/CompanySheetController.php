<?php

namespace App\Controller;

use App\Entity\CompanySheet;
use App\Form\CompanySheetType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanySheetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanySheetController extends AbstractController
{
    // Création d'une fiche société
    #[Route('/companysheet/create', name: 'app_companysheet_create')]
    public function app_companysheet_create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanySheetType::class, null, [
            'data_class' => CompanySheet::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companySheet = $form->getData();
            $em->persist($companySheet);
            $em->flush();
            return $this->redirectToRoute('app_projectleader_create');
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

    // Affichage de la fiche société 
    #[Route('/companysheet/{id}', name: 'app_companysheet_display', requirements: ['page' => '\d+'])]
    public function app_companysheet_display($id, CompanySheetRepository $companySheetRepository, CompanySheet $companySheet): Response
    {
        // Récupération de la liste des project leader à afficher dans la fiche société
        $projectLeaderList = $companySheet->getProjectLeaders();
        $projectLeaderNameList = [];
        foreach ($projectLeaderList as $projectLeaderName) {
            $projectLeaderNameList[] = $projectLeaderName->getName();
        }
        return $this->render('companySheet/displayCompanySheet.html.twig', [
            'company' => $companySheetRepository->find($id),
            'projectleadername' => $projectLeaderNameList,
        ]);
    }
}
