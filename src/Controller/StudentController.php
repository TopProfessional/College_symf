<?php

namespace App\Controller;

use App\Entity\Student;
use App\Service\DeleteEntityObjectHelper;
use App\Form\StudentWithUserType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/students")
 */
class StudentController extends AbstractController
{
    private DeleteEntityObjectHelper $deleteEntity;

    public function __construct(DeleteEntityObjectHelper $deleteEntity)
    {
        $this->deleteEntity = $deleteEntity;
    }

    /**
     * @Route(name="student_index", methods={"GET"})
     */
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render(
            'student/index.html.twig',
            [
                'students' => $studentRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="student_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $student = new Student();

        return $this->basicCreateUpdateMethod($request, $student, $entityManager);
    }

    /**
     * @Route("/{id}", name="student_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Student $student): Response
    {
        return $this->render(
            'student/show.html.twig',
            [
                'student' => $student,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="student_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Student $student, EntityManagerInterface $entityManager): Response
    {
        return $this->basicCreateUpdateMethod($request, $student, $entityManager);
    }

    /**
     * @Route("/{id}", name="student_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Student $student, EntityManagerInterface $entityManager, String $route = 'student_index'): Response {
        return $this->deleteEntity->deleteEntityObject(
            $request,
            $student,
            $entityManager,
            $route
        );
    }

    private function basicCreateUpdateMethod(
        Request $request,
        ?Student $student,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(StudentWithUserType::class, $student, ['action' => $request->getUri()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'student/edit.html.twig',
            [
                'student' => $student,
                'form' => $form->createView(),
            ]
        );
    }
}
