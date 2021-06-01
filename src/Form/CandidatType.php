<?php

namespace App\Form;

use App\Entity\Profil;
use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => "Nom",
                    'class' => "form-control"
                ],

            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'placeholder' => "Prénom",
                    'class' => "form-control"
                ],

            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => "Email",
                    'class' => "form-control"
                ],

            ])      
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
