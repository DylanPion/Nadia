<?php

namespace App\Controller;

use App\Entity\CompanySheet;
use App\Form\CompanySheetType;
use App\Entity\TotalAmountRepaidToDate;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanySheetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TotalAmountRepaidToDateRepository;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            $totalAmountRepaidToDate = new TotalAmountRepaidToDate();
            $totalAmountRepaidToDate->setTotalAmountRepaidToDate(0)
                ->setPayment(0)
                ->setDate(new DateTime())
                ->setCompanySheet($companySheet);
            $em->persist($totalAmountRepaidToDate);
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

    // Affichage d'une fiche société 
    #[Route('/companysheet/{id}', name: 'app_companysheet_display', requirements: ['page' => '\d+'])]
    public function app_companysheet_display($id, CompanySheetRepository $companySheetRepository, CompanySheet $companySheet, TotalAmountRepaidToDateRepository $totalAmountRepaidToDateRepository, Request $request, EntityManagerInterface $em, TotalAmountRepaidToDate $totalAmountRepaidToDate): Response
    {
        // Récupération de la liste des project leader à afficher dans la fiche société
        $projectLeaderNameList = [];
        $projectLeaderList = $companySheet->getProjectLeaders();
        foreach ($projectLeaderList as $projectLeaderName) {
            $projectLeaderNameList[] = $projectLeaderName->getName();
        }

        $builder = $this->createFormBuilder();

        $builder->add('companySheet', HiddenType::class)
            ->add('Payment', IntegerType::class, [
                'label' => false,
                "attr" => [
                    'placeholder' => "Nouveau Paiement"
                ]
            ])
            ->add('Date', DateType::class, [
                "label" => false,
                'widget' => 'single_text',
                "attr" => [
                    'class' => 'form-control',
                    'data-provide' => 'datepicker'
                ]
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Valider le reçu du paiement'
            ]);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $CS = $companySheetRepository->find($id); // Récupère l'objet companysheet selon l'id actuel de la page Une relations entre table étant représenté par un ID de type objet je récupère la fiche société en fonction de l'id de la page actuel 
            $totalAmountRepaidToDate = new TotalAmountRepaidToDate;
            $totalAmountRepaidToDate->setTotalAmountRepaidToDate($data['totalAmountRepaidToDate'])
                ->setPayment($data['Payment'])
                ->setDate($data['Date'])
                ->setCompanySheet($CS);

            $em->persist($totalAmountRepaidToDate);
            $em->flush();
        }

        return $this->render('companySheet/displayCompanySheet.html.twig', [
            'formView' => $form->createView(),
            'company' => $companySheetRepository->find($id),
            'projectleadername' => $projectLeaderNameList,
            'associationName' => $companySheetRepository->find($id)->getAssociation()->getName(),
            'totalAmountRepaid' => $totalAmountRepaidToDateRepository->getTotalAmountRepaidToDateById($id),
            'totalPaymentReceived' => $totalAmountRepaidToDateRepository->getTotalPaymentReceivedByCompany($id)
        ]);
    }
}
