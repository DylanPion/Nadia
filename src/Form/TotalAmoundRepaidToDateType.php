<?php

namespace App\Form;

use App\Entity\TotalAmountRepaidToDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('companySheet', HiddenType::class)
            ->add('Payment', IntegerType::class, [
                'label' => "Ajouter un Nouveau Paiement Reçu",
                "attr" => [
                    'placeholder' => "Nouveau Paiement"
                ]
            ])
            ->add('Date', DateType::class, [
                "label" => "Ajouter une Nouvelle Date",
                'widget' => 'single_text',
                "attr" => [
                    'class' => 'form-control',
                    'data-provide' => 'datepicker'
                ]
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TotalAmountRepaidToDate::class,
        ]);
    }
}
