<?php

namespace App\Controller;

use App\Repository\CompanySheetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CompanySheetRepository $companySheetRepository): Response
    {

        $companySheet = $companySheetRepository->findAll();
        return $this->render('home/home.html.twig', [
            'companySheet' => $companySheet,
        ]);
    }
}
