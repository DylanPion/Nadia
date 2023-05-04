<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('damageHistory', IntegerType::class, [
                'label' => 'Historique de casse, montant',
                "attr" => ["placeholder" => "Entrez l'Historique de de casse"]
            ])
            ->add('account2748', IntegerType::class, [
                'label' => 'Solde du cpte 2748 au 31/12',
                "attr" => ["placeholder" => "Entrez le Solde du cpte 2748 au 31/12"]
            ])
            ->add('account4671', IntegerType::class, [
                'label' => 'Solde du cpte 4671 au 31/12',
                "attr" => ["placeholder" => "Solde du cpte 4671 au 31/12"]
            ])
            ->add('Button', SubmitType::class, [
                'label' => 'Créer la nouvelle Comptabilité'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
