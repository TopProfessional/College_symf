<<<<<<< HEAD
<?php

namespace App\Form;

use App\Entity\Course;
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
=======
<?php

namespace App\Form;

use App\Entity\Course;
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
            // ->add('name')
            ->add('age')
            ->add('photo')
            ->add('mark')
            ->add('startDate')
            ->add('courses', EntityType::class , [
                'class' => Course::class,
                'by_reference' => false,
                'multiple' => true,
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
>>>>>>> ca1dbeb090c1917530f4a604a47d5d297dafbde5
