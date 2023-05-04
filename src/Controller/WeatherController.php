<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Entity\WeatherTable;
use App\Form\WeatherTableType;
use App\Form\WeatherType;
use App\Service\WeatherService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompanySheetRepository;
use App\Repository\WeatherTableRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WeatherController extends AbstractController
{
    #[Route('/weatherTableCreate', name: 'app_weatherTableCreate')]
    public function weatherTableCreate(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(WeatherTableType::class, null, [
            'data_class' => WeatherTable::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('app_association');
        }

        return $this->render('weather/createWeatherTable.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    #[Route('/weatherTableEdit', name: 'app_weatherTableEdit')]
    public function weatherTableEdit(Request $request, EntityManagerInterface $em, WeatherTableRepository $weatherTableRepository): Response
    {
        $weatherTable = $weatherTableRepository->findAll()[0];
        $form = $this->createForm(WeatherTableType::class, $weatherTable);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_association');
        }

        return $this->render('weather/editWeatherTable.html.twig', [
            'formView' => $form->createView(),
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
