<?php

namespace App\Form;

use App\Entity\Calendar;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('start',DateTimeType::class,[
                'date_widget' => 'single_text'
            ])
            ->add('end',DateTimeType::class,[
                'date_widget' => 'single_text'
            ])
            ->add('description')
            ->add('all_day')
            ->add('background_color')
            ->add('border_color')
            ->add('text_color')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
