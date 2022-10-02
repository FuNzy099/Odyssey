<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudonyme', TextType::class,[
                "attr" => [
                    'class' => "form-control"
                ],
            ])
            ->add('email', EmailType::class,[
                "attr" => [
                    'class' => "form-control"
                ],
            ])
            ->add('phoneNumber', TextType::class,[
                "attr" => [
                    'class' => "form-control"
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous devez accepter nos conditions d'utilisation",
                    ]),
                ],
            ])

            // RepeatedType permet de créer deux champs identiques dont les valeurs doivents correspondre à l'éxactitude sinon une erreur est renvoyé
            // documentation : https://symfony.com/doc/current/reference/forms/types/repeated.html
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller

                // mapped => false veut dire que le champs de ne sera pas stocké en base de donnée (On stock pas le repeat password)
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => "Votre mot de passe n'est pas identique.",
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    "attr" => [
                        'class' => "form-control"
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeter le mot de passe',
                    "attr" => [
                        'class' => "form-control"
                    ],
                ],
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
                    ]),
                ],
            ])

            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary submitProfil']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
