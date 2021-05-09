<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Entity\Recruteur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => "Titre",
                    'class' => "form-control"
                ],
            ])
            ->add('start',DateTimeType::class,[
                'date_widget' => 'single_text',
                'attr' => [
                    'class' => "form-control js-datepicker",
                ],
            ])
            ->add('end',DateTimeType::class,[
                'date_widget' => 'single_text',
                
                'attr' => [
                    'class' => "form-control js-datepicker"
                ],
                
            ])
            ->add('description',TextareaType::class,[
                'attr' => [
                    'placeholder' => "Description",
                    'class' => "form-control"
                ],
            ])
            ->add('all_day', CheckboxType::class, [
                'label'    => 'Toute la journée?',
                'required' => false,
                'attr' => [
                    'class' => "form-check-input",
                    'checked'=> false,
                ],
                
                
                'label_attr'=>[
                    'class'=> 'form-check-label'
                ]
            ])
            ->add('background_color', ColorType::class,[
                // 'html5'=> true,
                'attr' => [
                    // 'class' => "form-control"
                ],
            ])
            ->add('border_color', ColorType::class,[
                'attr' => [
                    // 'class' => "form-control"
                ],
            ])
            ->add('text_color', ColorType::class,[
                'attr' => [
                    // 'class' => "form-control"
                ],
            ])
            // ->add('recruteur', EntityType::class, [
            //     'required' => false,
                
            //     // 'choice_label' => 'Category',
            //     'class' => Recruteur::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
