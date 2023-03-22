<?php

namespace App\Form;

use App\Entity\CompanySheet;
use App\Entity\TotalAmountRepaidToDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TotalAmoundRepaidToDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('companysheet', EntityType::class, [
            //     'label' => 'Fiche Société',
            //     'placeholder' => '-- Choisir une Fiche Société --',
            //     'class' => CompanySheet::class,
            //     'choice_label' => 'companyName',
            // ])
            ->add('companySheet', HiddenType::class)
            ->add('totalAmountRepaidToDate', IntegerType::class, [
                'label' => "Total Remboursé à ce Jour :",
            ])
            ->add('Payment', IntegerType::class, [
                'label' => 'Paimenet Reçu par l\'Association :',
            ])
            ->add('Date', DateType::class, [
                "label" => 'Date du Paiement : ',
                'widget' => 'single_text',
                "attr" => [
                    'class' => 'form-control',
                    'data-provide' => 'datepicker'
                ]
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
