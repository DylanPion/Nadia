<?php

namespace App\Service;

use App\Entity\Association;
use App\Form\AssociationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TestForm extends AbstractController
{

    private $association;
    private $request;
    private $em;

    public function __construct(Association $association, Request $request, EntityManagerInterface $em)
    {
        $this->association = $association;
        $this->request = $request;
        $this->em = $em;
    }

    public function FormAsso()
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $association = $form->getData();
            $em->persist($association);
            $em->flush();
            return $this->redirectToRoute('app_association');
        };
    }
}
