<?php

namespace App\Form;

use App\Entity\CompanySheet;
use App\Entity\ProjectLeader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ProjectLeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du Porteur de projet',
                "attr" => ["placeholder" => "Entrer le nom du porteur de projet"]
            ])
            ->add('companysheet', EntityType::class, [
                'label' => 'Fiche Société',
                'placeholder' => '-- Choisir une Fiche Société --',
                'class' => CompanySheet::class,
                'choice_label' => 'companyName',
            ])

            ->add('Button', SubmitType::class, [
                'label' => 'Créer la fiche Société'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectLeader::class,
        ]);
    }
}
