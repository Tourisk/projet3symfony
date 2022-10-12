<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('couleur', ChoiceType::class, [
                'choices' => [
                    'Bleu'=> "blue",
                    'Noir'=> "black",
                    'Gris'=> "gray",
                    'Vert'=> "green",
                    'Blanc'=> "white",
                    'Jaune'=> "yellow",
                    'Rouge'=> "red",
                    'Autre'=> "Autre"
                ]
            ])
            ->add('taille', ChoiceType::class, [
                'choices' => [
                    'S'=> "S",
                    'M'=> "S",
                    'L'=> "L",
                    'XL'=> "XL",
                    'XXL'=> "XXL"
                ]
            ])
            ->add('collection', ChoiceType::class, [
                'choices' => [
                    'Homme'=> "Homme",
                    'Femme'=> "Femme",
                    'Enfant'=> "Enfant"
                ]
            ])
            ->add('photo')
            ->add('prix')
            ->add('stock')
            // ->add('date_enregistrement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
