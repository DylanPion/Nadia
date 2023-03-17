<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use App\Repository\CompanySheetRepository;
use App\Service\CalculService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AssociationController extends AbstractController
{
    // Affichage de la Liste des Associations.
    #[Route('/association', name: 'app_association')]
    public function app_association(AssociationRepository $associationRepository, CompanySheetRepository $companySheetRepository): Response
    {
        return $this->render('association/associationList.html.twig', [
            'associationList' => $associationRepository->findAll(),
            'TotalFni' => $companySheetRepository->getTotalFni(), // Fonction repository qui calcul la somme FNI engagé et FNI versé
        ]);
    }

    // Création d'une nouvelle Association.
    #[Route('/association/create', name: 'app_association_create')]
    public function app_association_create(Request $request, EntityManagerInterface $em): Response
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
    #[Route('/association/edit/{id}', name: 'app_association_edit', requirements: ['page' => '\d+'])]
    public function app_association_edit($id, Association $association, Request $request, EntityManagerInterface $em): Response
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
            'associationName' => $association->getName(),
            'formView' => $form->createView()
        ]);
    }

    //Suppresion d'une Association 
    #[Route('/association/delete/{id}', name: 'app_association_delete', requirements: ['page' => '\d+'])]
    public function app_association_delete(Association $association, EntityManagerInterface $em): Response
    {
        $em->remove($association);
        $em->flush();
        return $this->redirectToRoute('app_association');
    }

    // Affichage de la liste des fiche Société par Association
    #[Route('/association/{id}', name: 'app_association_display', requirements: ['page' => '\d+'])]
    public function app_association_display(
        $id,
        CompanySheetRepository $companySheetRepository,
        AssociationRepository $associationRepository,
        CalculService $calculService,
    ): Response {

        $test = $calculService->calculateRemainsToBePaid($id);
        // Liste des Montants : "Reste à payer"


        // Le terme 'association' dans find by représente le nom de la colonne qui établie la relation Association/Companysheet
        $company = $companySheetRepository->findBy(array('association' => $id));
        return $this->render('association/companyByAssociation.html.twig', [
            'company' => $company,
            'associationName' => $associationRepository->find($id)->getName(),
        ]);
    }
}
