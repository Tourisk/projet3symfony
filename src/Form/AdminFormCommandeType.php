<?php

namespace App\Form;

use App\Entity\Membre;
use App\Entity\Produit;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminFormCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            // ->add('montant')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'En cours de traitement' => 'En cours de traitement',
                    'Envoyé' => 'Envoyé',
                    'Livré' => 'Livré'
                ]
            ])
            // ->add('date_enregistrement')
            ->add('membre', EntityType::class, [
                'class' => Membre::class,
                'choice_label' => 'pseudo'
            ])
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'titre'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
