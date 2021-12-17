<?php

namespace App\Subscribers;

use App\Entity\Student;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DeleteImageSubscriber implements EventSubscriber
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private string $uploadsPath;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, string $uploadsPath)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->uploadsPath = $uploadsPath;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
        ];
    }

    public function preRemove(LifecycleEventArgs $args): void
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
