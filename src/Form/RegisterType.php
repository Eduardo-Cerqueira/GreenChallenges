<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Uid\Uuid;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countries = [
            'China' => 'China',
            'France' => 'France',
            'USA' => 'USA',
            'India' => 'India',
        ];

        $userChoice = [
            'User' => 'ROLE_USER',
            'Admin' => 'ROLE_ADMIN',
        ];

        $builder
            ->add('name', Type\TextType::class)
            ->add('surname', Type\TextType::class)
            ->add('username', Type\TextType::class)
            ->add('email', Type\EmailType::class)
            ->add('password', Type\PasswordType::class)
            ->add('country', ChoiceType::class, [
                'choices' => $countries,
                'placeholder' => 'Choose a country',
            ])
            ->add('roles', Type\ChoiceType::class, [
                'choices' =>  $userChoice,
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('save', Type\SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
