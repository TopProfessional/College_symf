<?php

namespace App\Subscribers;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordSubscriber implements EventSubscriber
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    /** 
     * @ORM\PrePersist
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        //dump('changed from prePersist callback!');
        //dump($args);

        
        $userEntity = $args->getEntity();//->getUsername();
        if ($userEntity instanceof User)
        {
            dump(' 1: '.$userEntity->getPassword());

            $plainpwd = $userEntity->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($userEntity, $plainpwd);
            $userEntity->setPassword($encoded);    

            dump(' 2: '.$userEntity->getPassword());


        }
    }
}


    