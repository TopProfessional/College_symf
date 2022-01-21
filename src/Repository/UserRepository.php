<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // for solo select (UserFilterType-> multiple=> false)

    /**
     * @param array<string,mixed> $filter
     *
     * @return User[]
     */
    public function findByFilter(?array $filter)//: array
    {
        $filter ??= [];
        $qb = $this->createQueryBuilder('users');

        $conditions = $qb->expr()->orX();

        if ($search = $filter['search'] ?? null) {
            $conditions->add('users.email LIKE :search or users.username LIKE :search');
            $qb->setParameter('search', '%'.$search.'%');
        }

        if ($role = $filter['role'] ?? null) {
            $conditions->add("users.roles LIKE :role");
            $qb->setParameter('role', '%"'.$role.'"%');
        }

        if ($conditions->count()) {
            $qb->andWhere($conditions);
        }

        return $qb;//->getQuery()->execute();
    }
}
