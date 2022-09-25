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

class AddEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'titre'
        ])
        // ->add('creationDate')
        ->add('startDate', DateTimeType::class, [
            'label' => 'Date de début',
            'attr'  => ['min' => ( new \DateTime() )->format('Y-m-d H:i')],
            'widget' => 'single_text'
        ])
        ->add('endDate', DateTimeType::class, [
            'label' => 'Date de fin',
            'attr' => ['min' => ( new \DateTime() )->format('Y-m-d H:i'), 'max' => ( new \dateTime("2099-01-01"))->format('Y-m-d H:i') ],
            'widget' => 'single_text'
        ])
        ->add('description', TextType::class, [
            'label' => 'Description'
        ])
        ->add('maxPlaces', IntegerType::class, [
            'label' => 'Nombre de places',
            'attr' =>  ['max' => 20, 'min' => 3],
        ])
        ->add('address', TextType::class, [
            'label' => 'Adresse'
        ])
        ->add('town', TextType::class, [
            'label' => 'Ville'
        ])
        ->add('zipCode', TextType::class, [
            'label' => 'Code postal'
        ])

        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary']
        ])
        // ->add('userCreator')
        // ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
