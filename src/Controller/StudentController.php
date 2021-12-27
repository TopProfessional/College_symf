<?php

namespace App\Controller;

use App\Entity\Student;
use App\Service\DeleteEntityObjectHelper;
use App\Service\UploaderHelper;
use App\Form\StudentWithUserType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



/**
 * @Route("/students")
 */
class StudentController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private DeleteEntityObjectHelper $deleteEntity;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, DeleteEntityObjectHelper $deleteEntity)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->deleteEntity = $deleteEntity;
    }

    /**
     * @Route(name="student_index", methods={"GET"})
     */
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="student_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(StudentWithUserType::class);
        $form->handleRequest($request);
        $student = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student/new.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="student_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="student_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Student $student, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(StudentWithUserType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($student);
            $entityManager->flush();

//            $uow = $entityManager->getUnitOfWork();
//            //$uow->computeChangeSet($entityManager->getClassMetadata(Student::class),$student);
//            $j =  $uow->getEntityChangeSet($student);
//            dd($j);
            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student/edit.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="student_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Student $student, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token')))
        {
            $entityManager->remove($student);
            $entityManager->flush();
        }
        //$this->deleteEntity->deleteEntityObject($request, $student, $entityManager); Удаляет нужного студента, и фото у всех студентов

        return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
    }
}
