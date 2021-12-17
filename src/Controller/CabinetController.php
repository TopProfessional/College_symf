<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CabinetController extends AbstractController
{
    private ?Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/cabinet", name="user_cabinet")
     */
    public function enterToTheCabinet( ): Response
    {
        $user = $this->security->getUser();

        return $this->render('cabinet/index.html.twig', [
            'user' => $user,
        ]);
    }
}
