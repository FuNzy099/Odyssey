<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      
        ->add('actualPassword', PasswordType::class,[
            'label' => 'Ancien mot de passe',
            'mapped' => false
        ])
        ->add('plainPassword', RepeatedType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller

            // mapped => false veut dire que le champs de ne sera pas stocké en base de donnée (On stock pas le repeat password)
            'mapped' => false,
            'type' => PasswordType::class,
            'invalid_message' => "Votre mot de passe n'est pas identique.",
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Nouveau mot de passe'],
            'second_options' => ['label' => 'Repeter le mot de passe'],
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe',
                ]),

                /*
                    Regex => Expression régulière

                    Une expression régulière est une séquence de caractères qui forme un modèle de recherche.
                */
                new Regex([
                    'pattern' => "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,64}$^", 
                    'message' => 'Minimum 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial'
                ]),
                
                new Length([
                    'maxMessage' => 'Votre mot de passe doit comporter au maximum {{ limit }} caractères',
                    /*
                        On limite la longueur maximale courante à 64 caractères en raison des limitations de certains algoritmes de hachage 
                        selon les recommandantion d'owasp => Open Web Application Security Project

                        DOCUMENTATION : https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html
                    */ 
                    'max' => 64,
                ])

            ]
            
        ])

        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary']
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
