<?php

namespace App\Form;

use App\Entity\CompanySheet;
use App\Entity\TotalAmountRepaidToDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TotalAmoundRepaidToDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companysheet', EntityType::class, [
                'label' => 'Fiche Société',
                'placeholder' => '-- Choisir une Fiche Société --',
                'class' => CompanySheet::class,
                'choice_label' => 'companyName',
            ])
            ->add('totalAmountRepaidToDate', IntegerType::class, [
                'label' => "Total Remboursé à ce Jour",
                "attr" => ['placeholder' => 'Entrez le Total Remboursé à ce jour']
            ])
            ->add('Payment', IntegerType::class, [
                'label' => 'Paimenet Reçu par l\'Association',
                "attr" => ['placeholder' => "Entrez la somme reçu par l'association"]
            ])
            ->add('Date', DateType::class, [
                "label" => 'Date du Paiement'
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Ajouter une ligne au Tableau'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TotalAmountRepaidToDate::class,
        ]);
    }
}
