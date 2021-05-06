<?php

namespace App\Form;

use App\Entity\Competence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competence', TextType::class, [
                'required' => false,
                'label' => 'Compétence',
                'attr' => [
                    'placeholder' => "Exp:PHP..",
                    'class' => "form-control"
                ],

            ])
            // ->add('color',ChoiceType::class, [
            //     'required' => false,
            //     'label' => 'Couleur',
            //     'expanded' => true,
            //     'choices' => [
            //         'Apple' => 1,
            //         'Banana' => 2,
            //         'Durian' => 3,
            //     ],
            //     'choice_attr' => [
            //         'Apple' => ['style' => 'color:red'],
            //         'Banana' => ['data-color' => 'Yellow'],
            //         'Durian' => ['data-color' => 'Green'],
            //     ],
            // ])
            // ->add('profils')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
        ]);
    }
}
