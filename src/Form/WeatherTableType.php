<?php

namespace App\Form;

use App\Entity\WeatherTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class WeatherTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('accountingProvision5', IntegerType::class, [
                'label' => "Echelle d'évaluation 5",
                "attr" => [
                    'placeholder' => "Entrer le pourcentage de provision comptable"
                ]
            ])
            ->add('accountingProvision4', IntegerType::class, [
                'label' => "Echelle d'évaluation 4",
                "attr" => [
                    'placeholder' => "Entrer le pourcentage de provision comptable"
                ]
            ])
            ->add('accountingProvision3', IntegerType::class, [
                'label' => "Echelle d'évaluation 3",
                "attr" => [
                    'placeholder' => "Entrer le pourcentage de provision comptable"
                ]
            ])
            ->add('accountingProvision2', IntegerType::class, [
                'label' => "Echelle d'évaluation 2",
                "attr" => [
                    'placeholder' => "Entrer le pourcentage de provision comptable"
                ]
            ])
            ->add('accountingProvision1', IntegerType::class, [
                'label' => "Echelle d'évaluation 1",
                "attr" => [
                    'placeholder' => "Entrer le pourcentage de provision comptable"
                ]
            ])
            ->add('accountingProvision0', IntegerType::class, [
                'label' => "Echelle d'évaluation 0",
                "attr" => [
                    'placeholder' => "Entrer le pourcentage de provision comptable"
                ]
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WeatherTable::class,
        ]);
    }
}
