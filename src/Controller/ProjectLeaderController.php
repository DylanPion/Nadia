<?php

namespace App\Controller;

use App\Entity\ProjectLeader;
use App\Form\ProjectLeaderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectLeaderController extends AbstractController
{
    // Création d'un porteur de projet
    #[Route('project-leader/create', name: 'app_projectleader_create')]
    public function app_projectleader_create(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProjectLeaderType::class, null, [
            'data_class' => ProjectLeader::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectleader = $form->getData();
            $em->persist($projectleader);
            $em->flush();
            return $this->redirectToRoute('app_association');
        }
        return $this->render(
            'projectleader/createProjectLeader.html.twig',
            [
                'formView' => $form->createView(),
            ]
        );
    }
}
