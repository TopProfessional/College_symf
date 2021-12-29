<?php

namespace App\Repository;

use App\Entity\UserClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserClass[]    findAll()
 * @method UserClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserClass::class);
    }

}
