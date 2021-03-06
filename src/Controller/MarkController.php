<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Mark;
use App\Form\MarkType;
use App\Repository\MarkRepository;
use App\Service\DeleteEntityObjectHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/marks")
 */
class MarkController extends AbstractController
{
    private DeleteEntityObjectHelper $deleteEntity;

    public function __construct(DeleteEntityObjectHelper $deleteEntity)
    {
        $this->deleteEntity = $deleteEntity;
    }

    /**
     * @Route(name="mark_index", methods={"GET"})
     */
    public function index(MarkRepository $markRepository): Response
    {
        return $this->render(
            'mark/index.html.twig',
            [
                'marks' => $markRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="mark_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_TEACHER")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mark = new Mark();
        return $this->basicCreateUpdateMethod($request, $mark, $entityManager);
    }

    /**
     * @Route("/{id}", name="mark_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Mark $mark): Response
    {
        return $this->render(
            'mark/show.html.twig',
            [
                'mark' => $mark,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="mark_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_TEACHER")
     */
    public function edit(Request $request, Mark $mark, EntityManagerInterface $entityManager): Response
    {
        return $this->basicCreateUpdateMethod($request, $mark, $entityManager);
    }

    /**
     * @Route("/{id}", name="mark_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_TEACHER")
     */
    public function delete(Request $request, Mark $mark, EntityManagerInterface $entityManager, String $route = 'mark_index'): Response
    {
        return $this->deleteEntity->deleteEntityObject(
            $request,
            $mark,
            $entityManager,
            $route
        );
    }

    private function basicCreateUpdateMethod(
        Request $request,
        ?Mark $mark,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(MarkType::class, $mark, ['action' => $request->getUri()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mark);
            $entityManager->flush();

            return $this->redirectToRoute('mark_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'mark/edit.html.twig',
            [
                'mark' => $mark,
                'form' => $form->createView(),
            ]
        );
    }
}
