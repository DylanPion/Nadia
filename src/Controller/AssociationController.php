<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use App\Repository\CompanySheetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AssociationController extends AbstractController
{
    // Affichage de la Liste des Associations.
    #[Route('/association', name: 'app_association')]
    public function index(AssociationRepository $associationRepository, CompanySheetRepository $companySheetRepository): Response
    {
        return $this->render('association/associationList.html.twig', [
            'associationList' => $associationRepository->findAll(),
            'TotalFni' => $companySheetRepository->getTotalFni(),
        ]);
    }

    // Création d'une nouvelle Association.
    #[Route('/association/create', name: 'app_association_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AssociationType::class, null, [
            'data_class' => Association::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $association = $form->getData();
            $em->persist($association);
            $em->flush();
            return $this->redirectToRoute('app_association');
        }

        return $this->render('association/createAssociation.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    // Modification d'une Association
    #[Route('/association/{id}/edit', name: 'app_association_edit')]
    public function edit($id, Association $association, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $association = $form->getData();
            $em->persist($association);
            $em->flush();
            return $this->redirectToRoute('app_association');
        };

        return $this->render('association/editAssociation.html.twig', [
            'association' => $association,
            'formView' => $form->createView()
        ]);
    }

    //Suppresion d'une Association 
    #[Route('/association/{id}/delete', name: 'app_association_delete')]
    public function delete(Association $association, EntityManagerInterface $em): Response
    {
        $em->remove($association);
        $em->flush();
        return $this->redirectToRoute('app_association');
    }

    // Affichage de la liste des fiche Société par Association
    #[Route('/association/{id}/show', name: 'app_association_show')]
    public function show($id, CompanySheetRepository $companySheetRepository): Response
    {
        // association dans find by est le nom de la colonne qui établie la relation Association/Companysheet
        $company = $companySheetRepository->findBy(array('association' => $id));
        return $this->render('association/showCompanyByAssociation.html.twig', [
            'company' => $company
        ]);
    }
}
