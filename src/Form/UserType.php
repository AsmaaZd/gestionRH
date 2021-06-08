<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                "required" => false,
                "attr" => [
                    "placeholder" => "Veuillez saisir votre email",
                    'class' => "form-control"
                ]
            ])

            ->add('password', PasswordType::class, [
                "required" => false,
                'label' => "Mot de passe",
                "attr" => [
                    "placeholder" => "Veuillez saisir votre password",
                    'class' => "form-control"
                    ]
                ])
            
            ->add('confirm_password', PasswordType::class, [
                "required" => false,
                'label' => "Confirmation du mot de passe",
                "attr" => [
                    "placeholder" => "Veuillez confirmer votre mot de passe",
                    'class' => "form-control"
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
