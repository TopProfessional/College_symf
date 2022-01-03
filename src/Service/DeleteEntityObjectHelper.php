<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteEntityObjectHelper extends AbstractController
{
    public function deleteEntityObject(Request $request, $entityObject, EntityManagerInterface $entityManager, String $route): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entityObject->getId(), $request->request->get('_token'))) {
            $entityManager->remove($entityObject);
            $entityManager->flush();
        }
        return $this->redirectToRoute($route, [], Response::HTTP_SEE_OTHER);
    }
}