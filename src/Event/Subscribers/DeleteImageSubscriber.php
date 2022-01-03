<?php

declare(strict_types=1);

namespace App\Event\Subscribers;

use App\Entity\Student;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;

class DeleteImageSubscriber implements EventSubscriber
{
    private string $uploadsPath;
    private EntityManagerInterface $entityManager;

    public function __construct(
        string $uploadsPath,
        EntityManagerInterface $entityManager
    ) {
        $this->uploadsPath = $uploadsPath;
        $this->entityManager = $entityManager;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
            Events::preUpdate,
        ];
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $student = $args->getEntity();

        if ($student instanceof Student) {
            $image = $student->getPhoto();

            if (!is_null($image)) {
                $this->removeImage($image);
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $student = $args->getEntity();

        if ($student instanceof Student) {
            if (isset($this->entityManager->getUnitOfWork()->getEntityChangeSet($student)['photo'])) {
                $image = $this->entityManager->getUnitOfWork()->getEntityChangeSet($student)['photo']['0'];
                $this->removeImage($image);
            }
        }
    }

    private function removeImage($image): void
    {
        $pathToFile = $this->uploadsPath.'/article_image/'.$image;
        $filesystem = new Filesystem();

        if ($filesystem->exists($pathToFile)) {
            $filesystem->remove([$pathToFile]);
        }
    }
}
