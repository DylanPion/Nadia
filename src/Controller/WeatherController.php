<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Form\WeatherType;
use App\Repository\CompanySheetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WeatherController extends AbstractController
{
    #[Route('/weather', name: 'app_weather')]
    public function index(): Response
    {
        return $this->render('weather/index.html.twig', [
            'controller_name' => 'WeatherController',
        ]);
    }

    // Création d'une nouvelle Météo 
    #[Route('/weather/create/{id}', name: 'app_weather_create')]
    public function create($id, Request $request, EntityManagerInterface $em, CompanySheetRepository $companySheetRepository): Response
    {
        $form = $this->createForm(WeatherType::class, null, [
            'data_class' => Weather::class
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $CS = $companySheetRepository->find($id);
            $data->setCompanySheet($CS);

            // Calcul Montant Provision Comptable
            $dateOfTheLastDayOfTheYear = $form->get('DateOfTheLastDayOfTheYear')->getData();
            $retainerPercentage = ($form->get("retainerPercentage")->getData()) / 100;
            $company = $companySheetRepository->find($id);
            $dateOfCe = $company->getDateOfCE();
            $remainToBeReceved = $company->getRemainsToBeReceived();
            $interval = $dateOfTheLastDayOfTheYear->diff($dateOfCe);
            $diffInMonths = $interval->y * 12 + $interval->m;
            if ($diffInMonths > 6) {
                $amountOfAccountingProvision = ($remainToBeReceved * $retainerPercentage) * 0.30;
            } else {
                $amountOfAccountingProvision = $remainToBeReceved;
            }
            $data->setAmountOfAccountingProvision($amountOfAccountingProvision);
            // dd($dateOfCe, $dateOfTheLastDayOfTheYear, $remainToBeReceved, $diffInMonths, $amountOfAccountingProvision);
            $em->persist($data);
            $em->flush();
            $id = $request->attributes->get("id");
            return $this->redirectToRoute('app_companysheet_display', ['id' => $id]);
        }




        return $this->render('weather/createWeather.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
