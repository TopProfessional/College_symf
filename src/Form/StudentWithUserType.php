<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\UserClass;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Service\UploaderHelper;

class StudentWithUserType extends AbstractType
{
    private UploaderHelper $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('age')
            ->add(
                'photo',
                FileType::class,
                [
                    'label' => 'Profile photo',

                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,

                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => false,

                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '1024k',
                            ]
                        ),
                    ],
                ]
            )
            ->add('startDate')
            ->add(
                'courses',
                EntityType::class,
                [
                    'class' => Course::class,
                    'by_reference' => false,
                    'multiple' => true,
                ]
            )
            ->add(
                'classes',
                EntityType::class,
                [
                    'class' => UserClass::class,
                    'multiple' => false,
                ]
            )
            ->add('user', UserType::class);

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event): void {
                $form = $event->getForm();
                $student = $form->getData();
                $uploadedFile = $form['photo']->getData();
                if ($uploadedFile) {
                    $newFilename = $this->uploaderHelper->uploadArticleImage($uploadedFile);
                    $student->setPhoto($newFilename);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Student::class,
            ]
        );
    }
}
