<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('age')
            ->add('photo')
            ->add('startDate')
<<<<<<< HEAD
            ->add('courses', EntityType::class, [
=======
            ->add('courses', EntityType::class , [
>>>>>>> ca1dbeb090c1917530f4a604a47d5d297dafbde5
                'class' => Course::class,
                'by_reference' => false,
                'multiple' => true,
            ])
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
