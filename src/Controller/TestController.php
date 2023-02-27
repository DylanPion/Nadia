<?php

namespace App\Controller;

use App\Repository\CompanySheetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
    public function index(CompanySheetRepository $companySheetRepository): Response
    {
        $test = $companySheetRepository->getTotalFni();
        // dd($test);
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
