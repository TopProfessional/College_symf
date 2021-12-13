<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Classes;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentWithUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('age')
            ->add('photo')
            ->add('startDate')
            ->add('courses', EntityType::class , [
                'class' => Course::class,
                'by_reference' => false,
                'multiple' => true,
            ])

            ->add('classes', EntityType::class , [
                'class' => Classes::class,
//                'by_reference' => false,
                'multiple' => false,
            ])

            ->add('user', UserType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}