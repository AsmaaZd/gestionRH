<?php

namespace App\Form;

use App\Entity\Equipement;
use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SalleType extends AbstractType
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
            ->add('capacity', NumberType::class, [
                'required' => false,
                'label' => 'Capacité',
                'attr' => [
                    'placeholder' => "Ex:2",
                    'class' => "form-control"
                ],

            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Ex: 8 eve...",
                    'class' => "form-control"
                ],

            ])
            ->add('equipement', EntityType::class, [
                "class" => Equipement::class,
                "choice_label" => "nom",
                'multiple' => true,
                
                'attr' => [
                    'class' => "form-control js-example-basic-multiple",
                    'data-placeholder' => "Exp:Ordinateur.." 
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
