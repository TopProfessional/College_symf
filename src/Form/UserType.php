<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'multiple' => true,
                    'expanded' => false,
                    'required' => true,
                    'choices' => [
                        'User' => User::ROLE_USER,
                        'Admin' => User::ROLE_ADMIN,
                        'Teacher' => User::ROLE_TEACHER,
                        'Student' => User::ROLE_STUDENT,
                    ],
                ]
            )
            ->add('username', null, ['error_bubbling' => true,])
            ->add('password');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
