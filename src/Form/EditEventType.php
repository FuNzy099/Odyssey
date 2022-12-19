<?php

namespace App\Form;

use App\Entity\Event;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                "attr" => [
                    'class' => "form-control"
                ],
            ])
            // ->add('creationDate')
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date de début',
                'attr'  => [
                    'min' => ( new \DateTime() )->format('Y-m-d H:i'),
                    'class' => "form-control"
                ],
                'widget' => 'single_text'
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin',
                'attr' => [
                    'min' => ( new \DateTime() )->format('Y-m-d H:i'), 'max' => ( new \dateTime("2099-01-01"))->format('Y-m-d H:i'),
                    'class' => "form-control"
                ],
                'widget' => 'single_text'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new Length([
                        'max' => 255,
                    ])
                ],
                "attr" => [
                    'class' => "form-control"
                ],
            ])
            ->add('maxPlaces', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' =>  [
                    'max' => 20, 'min' => 3,
                    'class' => "form-control"
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                "attr" => [
                    'class' => "form-control"
                ],
            ])
            ->add('town', TextType::class, [
                'label' => 'Ville',
                "attr" => [
                    'class' => "form-control"
                ],
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code postal',
                "attr" => [
                    'class' => "form-control"
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-primary submitProfil']
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
