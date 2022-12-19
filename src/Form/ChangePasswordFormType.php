<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),

                    /*
                        Regex => Expression régulière

                        Une expression régulière est une séquence de caractères qui forme un modèle de recherche.
                    */
                    new Regex([
                        'pattern' => "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$^", 
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

                ],
        
            ])

            /*
                Je fais un champ repeatedPassword dans le but d'effectuer une comparaison au sein de la function reset dans le ResetPasswordController.php
                Ce pourquoi je n'utilise pas un RepeatedType::class 
            */
            ->add('repeatedPassword', PasswordType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Repeter le mot de passe',
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

