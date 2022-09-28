<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                "attr" => [
                    'class' => "form-control"
                ],
            ])
            ->add('pseudonyme', TextType::class,[
                "attr" => ['class' => "form-control"],
            ])
            ->add('phoneNumber', TextType::class,[
                "attr" => ['class' => "form-control"],
            ])
            ->add('avatar', FileType::class, [
                'attr' => ['class' => "form-control"],
                'data_class' => null,
                'required' => false,
                'mapped' => false,

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