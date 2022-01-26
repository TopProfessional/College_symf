<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;


class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('per_page', null, ['required' => false])
            ->add('search', null, ['required' => false])
            ->add(
                'role',
                ChoiceType::class,
                [
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'choices' => [
                        // 'All' => null,
                        'Admin' => User::ROLE_ADMIN,
                        'Teacher' => User::ROLE_TEACHER,
                        'Student' => User::ROLE_STUDENT,
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => null,
                'csrf_protection'   => false,
                'method' => Request::METHOD_GET,
            ]
        );
    }
}
