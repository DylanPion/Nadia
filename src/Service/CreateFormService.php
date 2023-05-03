<?php

namespace App\Service;

use Twig\Environment;
use App\Entity\Association;
use App\Form\AssociationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class CreateFormService
{
    private $entityManager;
    private $formFactory;
    private $urlGenerator;
    private $twig;
    private $request;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, UrlGeneratorInterface $urlGenerator, Environment $twig, Request $request)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        $this->request = $request;
    }

    // Formulaire pour créer une Association 
    public function createAssociation()
    {
        $form = $this->formFactory->createBuilder(AssociationType::class, new Association())
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $association = $form->getData();
            $this->entityManager->persist($association);
            $this->entityManager->flush();
            $url = $this->urlGenerator->generate('app_association');
            return new Response('', 302, ['Location' => $url]);
        }

        $content = $this->twig->render('association/createAssociation.html.twig', [
            'formView' => $form->createView(),
        ]);

        return new Response($content);
    }
}
