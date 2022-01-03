<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Service\DeleteEntityObjectHelper;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/courses")
 */
class CourseController extends AbstractController
{
    private DeleteEntityObjectHelper $deleteEntity;

    public function __construct(DeleteEntityObjectHelper $deleteEntity)
    {
        $this->deleteEntity = $deleteEntity;
    }

    /**
     * @Route(name="course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render(
            'course/index.html.twig',
            [
                'courses' => $courseRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="course_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();

        return $this->basicCreateUpdateMethod($request, $course, $entityManager);
    }

    /**
     * @Route("/{id}", name="course_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Course $course): Response
    {
        return $this->render(
            'course/show.html.twig',
            [
                'course' => $course,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="course_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        return $this->basicCreateUpdateMethod($request, $course, $entityManager);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Course $course, EntityManagerInterface $entityManager, String $route = 'course_index'): Response
    {
        return $this->deleteEntity->deleteEntityObject(
            $request,
            $course,
            $entityManager,
            $route
        );
    }

    private function basicCreateUpdateMethod(
        Request $request,
        ?Course $course,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CourseType::class, $course, ['action' => $request->getUri()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'course/edit.html.twig',
            [
                'course' => $course,
                'form' => $form->createView(),
            ]
        );
    }
}
