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
    public function show($id, CompanySheetRepository $companySheetRepository): Response
    {
        return $this->render('companySheet/showCompanySheet.html.twig', [
            'company' => $companySheetRepository->find($id)
        ]);
    }
}
