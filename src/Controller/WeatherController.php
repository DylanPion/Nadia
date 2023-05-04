<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Form\WeatherType;
use App\Service\WeatherService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanySheetRepository;
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
    public function create($id, Request $request, EntityManagerInterface $em, CompanySheetRepository $companySheetRepository, WeatherService $weatherService): Response
    {
        $form = $this->createForm(WeatherType::class, null, [
            'data_class' => Weather::class
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $CS = $companySheetRepository->find($id);
            $data->setCompanySheet($CS);

            $amountOfAccountingProvision = $weatherService->calculateAmountOfAccountingProvision($form, $CS);
            $data->setAmountOfAccountingProvision($amountOfAccountingProvision);

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
