<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Repository\CompanySheetRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountingController extends AbstractController
{
    #[Route('/accounting', name: 'app_accounting')]
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('accounting/account.html.twig', [
            'controller_name' => 'AccountingController',
            'account' => $accountRepository->findAll()
        ]);
    }

    // Création d'une nouvelle Comptabilité
    #[Route('/account/create', name: 'app_account_create')]
    public function app_association_create(Request $request, EntityManagerInterface $em, CompanySheetRepository $companySheetRepository): Response
    {
        $form = $this->createForm(AccountType::class, null, [
            'data_class' => Account::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $account = $form->getData();
            $year = new DateTime();
            $account->setYear($year)
                ->setTotalAmountFniPaid($companySheetRepository->getTotalFNIAmountPaid())
                ->setTotalAmountRepaidToDate($companySheetRepository->getTotalAmountRepaidToDate());
            $em->persist($account);
            $em->flush();
            return $this->redirectToRoute('app_accounting');
        }

        return $this->render('accounting/createAccount.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
