<?php

namespace App\Form;

use App\Entity\Teacher;
use App\Entity\UserClass;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add(
                'teachers',
                EntityType::class,
                [
                    'class' => Teacher::class,
                    'by_reference' => false,
                    'multiple' => true,
                ]
            );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UserClass::class,
            ]
        );
    }
}
