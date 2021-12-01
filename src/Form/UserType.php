<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    // {
    //     $builder
    //         ->add('email')
    //         ->add('roles', ChoiceType::class,  [
    //             'multiple' => true,
    //             //'multiple' => false,
    //             'expanded' => false,
    //             'choices' => [
    //                 'User' => 'ROLE_USER',
    //                 'Admin' => 'ROLE_ADMIN',
    //                 'Teacher' => 'ROLE_TEACHER',
    //                 'Student' => 'ROLE_STUDENT'
    //             ]
    //         ])
    //         ->add('username')
    //         ->add('password')
    //     ;
    // }

    {
        $builder
        ->add('email')
        ->add('roles', ChoiceType::class, [
            'label' => 'roles',
            'multiple' => true,
            'expanded' => false,
            
            'choices' => [
                                'User' => 'ROLE_USER',
                                'Admin' => 'ROLE_ADMIN',
                                'Teacher' => 'ROLE_TEACHER',
                                'Student' => 'ROLE_STUDENT'
                            ],
            'translation_domain' => 'common',
            'label_attr' => ['class' => 'cursor_text'],
            'attr' => [
                'style' => 'some style'
            ]
        ])
        // ->add('check_box1')
        ->add('username')
        ->add('password')
    ;
 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
