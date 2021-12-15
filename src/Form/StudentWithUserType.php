<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Classes;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class StudentWithUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('age')
//            ->add('photo')

        //...
            ->add('photo', FileType::class, [
                    'label' => 'Profile photo',

                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,

                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => false,

                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
//                            'mimeTypes' => [
//                                'application/png',
//                                'application/jpg',
//                            ],
//                            'mimeTypesMessage' => 'Please upload a valid png/jpg document',
                        ])
                    ],
                ])
                    // ...

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
