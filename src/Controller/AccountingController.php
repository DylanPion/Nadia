<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountingController extends AbstractController
{
    #[Route('/accounting', name: 'app_accounting')]
    public function index(): Response
    {
        return $this->render('accounting/index.html.twig', [
            'controller_name' => 'AccountingController',
        ]);
    }
}
