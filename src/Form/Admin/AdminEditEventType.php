<?php

namespace App\Form;

use DateTime;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminEditEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'évènement',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
  
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'max' => 255
                ]
            ])
            
            // ->add('startDate', DateTimeType::class, [
            //     'label' => 'Date de début',
            //     'widget' => 'single_text'
            // ]) 

            // ->add('endDate', DateTimeType::class, [
            //     'label' => 'Date de début',
            //     'widget' => 'single_text'
            // ]) 

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary submitProfil'
                ]
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
