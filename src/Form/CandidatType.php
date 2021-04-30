<?php

namespace App\Form;

use App\Entity\Profil;
use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Nom",
                    'class' => "form-control"
                ],

            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Prenom",
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
