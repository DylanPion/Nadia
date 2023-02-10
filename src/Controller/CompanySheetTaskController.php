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

class CompanySheetTaskController extends AbstractController
{
    #[Route('/companysheet/create', name: 'app_companysheet_create')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanySheetType::class, null, [
            'data_class' => CompanySheet::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companySheet = $form->getData();
            $em->persist($companySheet);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('companysheet/create.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    #[Route('/companysheet/{id}/edit', name: 'app_companysheet_edit')]
    public function edit($id, CompanySheet $companySheet, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanySheetType::class, $companySheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companySheet = $form->getData();
            $em->persist($companySheet);
            $em->flush();
            return $this->redirectToRoute('app_home');
        };

        return $this->render('companysheet/edit.html.twig', [
            'companySheet' => $companySheet,
            'formView' => $form->createView()
        ]);
    }

    #[Route('/companysheet/{id}/delete', name: 'app_companysheet_delete')]
    public function delete(CompanySheet $companySheet, EntityManagerInterface $em): Response
    {
        $em->remove($companySheet);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/companysheet/{id}/show', name: 'app_companysheet_show')]
    public function show($id, CompanySheetRepository $companySheetRepository): Response
    {
        return $this->render('companysheet/show.html.twig', [
            'company' => $companySheetRepository->find($id)
        ]);
    }
}
