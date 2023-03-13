<?php

namespace App\Form;

use App\Entity\Agreement;
use App\Entity\Association;
use App\Entity\CompanySheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class CompanySheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('Association', EntityType::class, [
                'label' => 'Association',
                'placeholder' => '-- Choisir une association --',
                'class' => Association::class,
                'choice_label' => 'name',
            ])
            ->add('CompanyName', TextType::class, [
                'label' => "Nom de la société",
                "attr" => ["placeholder" => "Entrez le nom de la société"]
            ])
            ->add('Agreement', EntityType::class, [
                'label' => 'Convention',
                'placeholder' => '-- Choisir une convention --',
                'class' => Agreement::class,
                'choice_label' => 'number',
            ])
            ->add('LoanStatus', ChoiceType::class, [
                'label' => 'Statut du prêt',
                'choices' => [
                    'Engagé' => 'Engagé',
                    'Recouvrement' => 'Recouvrement',
                    'Contentieux' => 'Contentieux',
                    'Cloture avec perte' => 'Cloture avec perte'
                ]
            ])
            ->add('DateOfCE', DateType::class, [
                'label' => 'Date du CE',
                'format' => 'dd MMM yyyy',
                "attr" => ['placeholder' => 'Entrez la Date du CE', "class" => "date"]
            ])
            ->add('RepaymentStartDate', DateType::class, [
                'label' => 'Date du début du remboursement',
                'format' => 'dd MMM yyyy',
                "attr" => ["placeholder" => "Entrez la Date du début du remboursement", "class" => "date"]
            ])
            ->add('RepaymentEndDate', DateType::class, [
                'label' => 'Date de fin du remboursement',
                'format' => 'dd MMM yyyy',
                "attr" => ['placeholder' => "Entrez la Date de fin du remboursement", "class" => "date"]
            ])
            ->add('FNIAmountRequested', IntegerType::class, [
                'label' => 'Montant FNI engagé',
                "attr" => ['placeholder' => "Entrez le Montant FNI demandé"]
            ])
            ->add('FniAmountPaid', IntegerType::class, [
                'label' => 'Montant FNI Versé',
                "attr" => ['placeholder' => "Entrez le Montant versé"]
            ])
            ->add('PaymentOne', IntegerType::class, [
                'label' => 'Premier Versement',
                "attr" => ['placeholder' => "Entrez la somme du versement effectué"]
            ])
            ->add('PaymentOneDate', DateType::class, [
                'label' => 'Date du versement',
                'format' => 'dd MMM yyyy',
                "attr" => ['placeholder' => "Entrez la Date du versement effectué", "class" => "date"]
            ])
            ->add('PaymentTwo', IntegerType::class, [
                'label' => 'Second Versement',
                'required' => false,
                "attr" => ['placeholder' => "Entrez la somme du second versement effectué"]
            ])
            ->add('PaymentTwoDate', DateType::class, [
                'label' => 'Date du Second Versemenet',
                'required' => false,
                'empty_data' => null,
                'format' => 'dd MMM yyyy',
                "attr" => ['placeholder' => "Entrez la Date du second versement effectué", "class" => "date"]
            ])
            ->add('TotalAmountRepaidToDate', IntegerType::class, [
                'label' => 'Montant total remboursé à ce jour ',
                "attr" => ['placeholder' => "Entrez la somme du montant total remboursé à ce jour "]
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Créer le nouveau porteur de projet'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanySheet::class,
        ]);
    }
}
