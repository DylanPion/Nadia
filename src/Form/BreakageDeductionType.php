<?php

namespace App\Form;

use App\Entity\BreakageDeduction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BreakageDeductionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('BreakageDeduction', IntegerType::class, [
                'label' => 'Historique de Casse :',
                "attr" => ["placeholder" => "Entrez l\'Historique de Casse"]
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Créer le nouvelle Historique de Casse'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BreakageDeduction::class,
        ]);
    }
}
