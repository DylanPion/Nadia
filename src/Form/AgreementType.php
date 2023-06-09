<?php

namespace App\Form;

use App\Entity\Agreement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AgreementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Number', IntegerType::class, [
                'label' => 'Numéro de la nouvelle Convention :',
                "attr" => ["placeholder" => "Entrez le nom de la nouvelle Convention"]
            ])
            ->add('amountRequestedForTheAgreement', IntegerType::class, [
                'label' => 'Montant demandés par la CDC/BPI :',
                "attr" => ["placeholder" => "Entrez le Montant demandés par la CDC/BPI"]
            ])
            ->add('cashFund', IntegerType::class, [
                'label' => 'Montant reçus par la CDC/BPI :',
                "attr" => ["placeholder" => "Entrez le Montant reçus par la CDC/BPI"]
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Création d\'une nouvelle convention'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agreement::class,
        ]);
    }
}
