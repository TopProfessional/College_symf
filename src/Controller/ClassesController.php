<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Form\ClassesType;
use App\Repository\ClassesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/classes")
 */
class ClassesController extends AbstractController
{
    /**
     * @Route(name="classes_index", methods={"GET"})
     */
    public function index(ClassesRepository $classesRepository): Response
    {
        return $this->render(
            'classes/index.html.twig',
            [
                'classes' => $classesRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="classes_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $class = new Classes();
        $form = $this->createForm(ClassesType::class, $class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($class);
            $entityManager->flush();

            return $this->redirectToRoute('classes_index', [], Response::HTTP_SEE_OTHER);
        }

//        $this->basicEntityMethods($request, $form,  $class, $entityManager, $defaultReturn);

        return $this->render(
            'classes/new.html.twig',
            [
                'class' => $class,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="classes_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Classes $class): Response
    {
        return $this->render(
            'classes/show.html.twig',
            [
                'class' => $class,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="classes_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Classes $class, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClassesType::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$this->basicEntityMethods($class, $entityManager);
            $entityManager->persist($class);
            $entityManager->flush();

            return $this->redirectToRoute('classes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'classes/edit.html.twig',
            [
                'class' => $class,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="classes_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Classes $class, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$class->getId(), $request->request->get('_token'))) {
            $entityManager->remove($class);
            $entityManager->flush();
        }

        return $this->redirectToRoute('classes_index', [], Response::HTTP_SEE_OTHER);
    }

    private function basicEntityMethods(Classes $class, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->persist($class);
        $entityManager->flush();

        return $this->redirectToRoute('classes_index', [], Response::HTTP_SEE_OTHER);
    }
}
