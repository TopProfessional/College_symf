<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFilterType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\DeleteEntityObjectHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    private DeleteEntityObjectHelper $deleteEntity;

    public function __construct(DeleteEntityObjectHelper $deleteEntity)
    {
        $this->deleteEntity = $deleteEntity;
    }

    /**
     * @Route(name="user_index", methods={"GET", "POST"})
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $form = $this->createForm(UserFilterType::class, null, ['action' => $this->generateUrl('user_index'), 'method' => Request::METHOD_GET ]);
        $form->handleRequest($request);
       
        $maxPerPage = 3;
        $currPage = intval($request->query->get('page'));
        
        $queryBuilder = $userRepository->findByFilter($form->getData());
        $pagerfanta = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pagerfanta->setMaxPerPage($maxPerPage);
  
        if(isset($currPage)){
            $pagerfanta->setCurrentPage($currPage);
        }
        
        return $this->render(
            'user/index.html.twig',
            [
                'pager' => $pagerfanta,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/new", name="user_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        return $this->basicCreateUpdateMethod($request, $user, $entityManager);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(User $user): Response
    {
        return $this->render(
            'user/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        return $this->basicCreateUpdateMethod($request, $user, $entityManager);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"}, requirements={"id"="\d+"})@IsGranted("ROLE_ADMIN")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, String $route = 'user_index'): Response
    {
        return $this->deleteEntity->deleteEntityObject(
            $request,
            $user,
            $entityManager,
            $route
        );
    }

    private function basicCreateUpdateMethod(
        Request $request,
        ?User $user,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(UserType::class, $user, ['action' => $request->getUri()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
