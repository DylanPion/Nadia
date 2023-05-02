<?php

namespace App\Form;

use App\Entity\Weather;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class WeatherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weatherYear', DateType::class, [
                'label' => 'Année Météo',
                'widget' => 'single_text',
                "attr" => [
                    'placeholder' => 'Entrez l\'Année Météo',
                    'class' => 'form-control',
                    'data-provide' => 'datepicker'
                ]
            ])
            ->add('DateOfTheLastDayOfTheYear', DateType::class, [
                'label' => 'Date du dernier jour de l\'année',
                'widget' => 'single_text',
                "attr" => [
                    'placeholder' => 'Entrez la date du dernier jour de l\'année',
                    'class' => 'form-control',
                    'data-provide' => 'datepicker'
                ]
            ])
            ->add('unpaidNumber', IntegerType::class, [
                'label' => 'Nombre d\'Impayés',
                "attr" => ['placeholder' => "Entrez le Nombre d'Impayés"]
            ])
            ->add('assessmentScale', IntegerType::class, [
                'label' => 'Echelle Evaluation',
                "attr" => ['placeholder' => "Entrez le Nombre de l'Echelle Evaluation"]
            ])
            ->add('retainerPercentage', IntegerType::class, [
                'label' => 'Pourcentage Provision',
                "attr" => ['placeholder' => "Entrez le Pourcentage Provision"]
            ])
            ->add('LoanStatus', ChoiceType::class, [
                'label' => 'Statut du prêt',
                'choices' => [
                    'Engagé' => 'Engagé',
                    'Recouvrement' => 'Recouvrement',
                    'Contentieux' => 'Contentieux',
                    'Cloture avec perte' => 'Cloture avec perte',
                    'Liquidation' => 'Liquidation'
                ],
                'attr' => [
                    'class' => 'form-control loan-status', // Ajouter une classe ici
                ],
            ])
            ->add('liquidationDate', DateType::class, [
                'label' => 'Date de Liquidation',
                'widget' => 'single_text',
                "attr" => [
                    'placeholder' => 'Entrez la Date de Liquidation',
                    'class' => 'form-control C',
                    'data-provide' => 'datepicker'
                ],
                'required' => false, // Ajouter required false pour rendre le champ facultatif
            ])
            ->add('bpiGuarantee', ChoiceType::class, [
                'label' => 'Garantie BPI',
                'choices' => [
                    'Oui' => 'Oui',
                    'Non' => 'Non',
                ]
            ])
            ->add('comment', TextType::class, [
                'label' => "Commentaire",
                "attr" => ["placeholder" => "Entrez un commentaire"]
            ])
            ->add('amountOfDamage', IntegerType::class, [
                'label' => 'Montant de la Casse',
                "attr" => ['placeholder' => "Entrez le Montant de la Casse"]
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Valider la Fiche Société'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Weather::class,
        ]);
    }
}
