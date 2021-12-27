<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DeleteEntityObjectHelper extends AbstractController
{
    public function deleteEntityObject(Request $request, $entityObject, EntityManagerInterface $entityManager): void
    {
        if ($this->isCsrfTokenValid('delete'.$entityObject->getId(), $request->request->get('_token')))
        {
            $entityManager->remove($entityObject);
            $entityManager->flush();
        }
    }
}