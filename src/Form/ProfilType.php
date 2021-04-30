<?php

namespace App\Form;

use App\Entity\Competence;
use App\Entity\Profil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['candidat'] == true or $options['recruteur'] == true)
        {
            $builder
            ->add('nbAnneesExp', NumberType::class, [
                'label' => "Nombre d'années d'experience",
                'attr' => [
                    'placeholder' => "5",
                    'invalid_message'=> "doit être un nombre"

                ],
            ])
            ->add('competence', EntityType::class, [
                "class" => Competence::class,
                "choice_label" => "competence",
                'multiple' => true,
                "expanded" => true, // checkbox
                'attr' => [
                    'class' => "select2",
                    'data-placeholder' => "Sélectionnez une ou des matières" 
                ]
            ])
           
        ;
        }
        else{
            $builder
            ->add('nbAnneesExp')
            ->add('competence');
        }
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
            'candidat' => false,
            'recruteur' => false,
        ]);
    }
}
