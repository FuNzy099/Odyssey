<?php

namespace App\Form;

use App\Data\SearchAdmin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('title')
            // ->add('creationDate')
            // ->add('startDate')
            // ->add('endDate')
            // ->add('description')
            // ->add('maxPlaces')
            // ->add('address')
            // ->add('town')
            // ->add('zipCode')
            ->add('userCreator', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => 'Rechercher'
                    ]
                ])
            // ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchAdmin::class,
        ]);
    }
}
