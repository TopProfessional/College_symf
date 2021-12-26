<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Form\TeacherWithUserType;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/teachers")
 */
class TeacherController extends AbstractController
{
    /**
     * @Route(name="teacher_index", methods={"GET"})
     */
    public function index(TeacherRepository $teacherRepository): Response
    {
        return $this->render('teacher/index.html.twig', [
            'teachers' => $teacherRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="teacher_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherWithUserType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($teacher);
            $entityManager->flush();

            return $this->redirectToRoute('teacher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teacher/new.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="teacher_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Teacher $teacher): Response
    {
        return $this->render('teacher/show.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="teacher_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Teacher $teacher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeacherWithUserType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($teacher);
            $entityManager->flush();

            return $this->redirectToRoute('teacher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teacher/edit.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="teacher_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Teacher $teacher, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->request->get('_token'))) {
            $entityManager->remove($teacher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('teacher_index', [], Response::HTTP_SEE_OTHER);
    }
}
