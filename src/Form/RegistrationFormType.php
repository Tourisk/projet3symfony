<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label'=> 'Pseudo'
            ])
            ->add('nom', TextType::class, [
                'label'=> 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label'=> 'Prénom'
            ])
            ->add('email', TextType::class, [
                'label'=> 'E-mail'
            ])
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'Monsieur'=> "M",
                    'Madame'=> "F",
                    'Autre'=> "T"
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options'=>['label'=> 'Mot de passe'],
                'second_options'=>['label'=>'Confirmez votre mot de passe'],
                'invalid_message'=> 'Les mots de passe ne correspondent pas.',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                //mapped => false indique à symfony de ne pas vérifier dans l'entity que cette propriété existe
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            // ->add('roles', ChoiceType::class, [
            //     'choices'=> [
            //         'Admin'=> "ROLE_ADMIN",
            //         'Membre'=> "ROLE_USER"
            //     ],
            //         'expanded'=>true,
            //         'multiple'=>true
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
