<?php

namespace App\Form;

use DateTime;
use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => 'Rechercher'
                    ]
                ])
                
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date de début',
                'required' => false,
                'attr'  => [
                    'class' => "form-control",
                    'min' => ( new \DateTime() )->format('Y-m-d H:i'), 'max' => ( new \dateTime("2099-01-01"))->format('Y-m-d H:i')
                    ],
                'widget' => 'single_text'
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin',
                'required' => false,
                'attr' => [
                    'class' => "form-control",
                    'min' => ( new \DateTime() )->format('Y-m-d H:i'), 'max' => ( new \dateTime("2099-01-01"))->format('Y-m-d H:i')
                    ],
                'widget' => 'single_text'
            ])

            ->add('town', TextType::class, [
                "attr" => ['class' => "form-control"],
                'label' => 'Ville',
                'required' => false,
            ])
            ->add('zipCode', TextType::class, [
                "attr" => ['class' => "form-control"],
                'label' => 'Code postal',
                'required' => false,
            ])

 
        ;
    }

    /*
        Cette function permet de configurer les differentes options lié à ce formulaire

        Le composant OptionResolver est un remplacement amélioré de la fonction PHP array_replace

        DOCUMENTATION:
            OptionResolver : https://symfony.com/doc/5.4/components/options_resolver.html
    */ 

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Permets de définir qu'elle classe sert à representer nos données
            'data_class' => SearchData::class,
            // Permets de mettre les paramettres dans l'URL pour que l'utilisateur puisse partager une recherche
            'method' => 'GET',
            // Permets de désactiver la protection CSRF, etant donnée que nous sommes dans un formulaire de recherche il n'y a pas de problème de cross-site scripting
            'csrf_protection' => false
        ]);
    }

    /*
        Cette function permet d'obtenir une url la plus propre possible car par defaut il vas tous mettre dans un tableau qui ce nommera SearchData
    */
    public function getBlockPrefix()
    {
        return '';
    }
}
