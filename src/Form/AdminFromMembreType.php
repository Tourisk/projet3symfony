<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class AdminFormMembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label'=> 'E-mail'
            ])
            ->add('roles', ChoiceType::class, [
                'choices'=> [
                    'Admin'=> "ROLE_ADMIN",
                    'Membre'=> "ROLE_USER"
                ],
                    'expanded'=>true,
                    'multiple'=>true
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options'=>['label'=> 'Mot de passe'],
                'second_options'=>['label'=>'Confirmez votre mot de passe'],
                'invalid_message'=> 'Les mots de passe ne correspondent pas.',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                //mapped => false indique à symfony de ne pas vérifier dans l'entity que cette propriété existe
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label'=> 'Pseudo'
            ])
            ->add('nom', TextType::class, [
                'label'=> 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label'=> 'Prénom'
            ])
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'M' => 'Homme',
                    'Mme' => 'Femme'
                ]
            ])
            // ->add('date_enregistrement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
