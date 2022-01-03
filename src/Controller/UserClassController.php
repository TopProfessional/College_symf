<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserClass;
use App\Form\UserClassType;
use App\Repository\UserClassRepository;
use App\Service\DeleteEntityObjectHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/classes")
 */
class UserClassController extends AbstractController
{
    private DeleteEntityObjectHelper $deleteEntity;

    public function __construct(DeleteEntityObjectHelper $deleteEntity)
    {
        $this->deleteEntity = $deleteEntity;
    }

    /**
     * @Route(name="classes_index", methods={"GET"})
     */
    public function index(UserClassRepository $classesRepository): Response
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
        $class = new UserClass();
        return $this->basicCreateUpdateMethod($request, $class, $entityManager);
    }

    /**
     * @Route("/{id}", name="classes_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(UserClass $class): Response
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
    public function edit(Request $request, UserClass $class, EntityManagerInterface $entityManager): Response
    {
        return $this->basicCreateUpdateMethod($request, $class, $entityManager);
    }

    /**
     * @Route("/{id}", name="classes_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, UserClass $class, EntityManagerInterface $entityManager, String $route = 'classes_index'): Response
    {
        return $this->deleteEntity->deleteEntityObject(
            $request,
            $class,
            $entityManager,
            $route
        );
    }

    private function basicCreateUpdateMethod(
        Request $request,
        ?UserClass $class,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(UserClassType::class, $class, ['action' => $request->getUri()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
}
