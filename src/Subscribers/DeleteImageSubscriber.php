<?php

namespace App\Subscribers;

use App\Entity\Student;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class DeleteImageSubscriber implements EventSubscriber
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private string $uploadsPath;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, string $uploadsPath, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->uploadsPath = $uploadsPath;
        $this->entityManager = $entityManager;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
//            Events::preUpdate,
        ];
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $this->removeImage($args);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        //$this->removeImage($args);
//        $student = $args->getEntity();
//        $j =  $this->entityManager->getUnitOfWork()->getEntityChangeSet($student);
//        dd($j);
    }


    private function removeImage($args): void
    {
        $student = $args->getEntity();
        if ($student instanceof Student)
        {
            $image = $student->getPhoto();
            $pathToFile = $this->uploadsPath.'/article_image/'.$image;
            $filesystem = new Filesystem();

            if($filesystem->exists($pathToFile))
            {
                $filesystem->remove([$pathToFile]);
            }
        }
    }
}
