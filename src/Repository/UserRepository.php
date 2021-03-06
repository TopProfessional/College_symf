<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\Criteria;

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

    /**
     * @param array<string,mixed>|null $filter
     *
     * @return QueryBuilder
     */
    public function findByFilter(?array $filter, $field = null, $sort = null): QueryBuilder
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
        
        $params = ['email', 'roles', 'username', 'id'];
        $orderBy = [Criteria::DESC, Criteria::ASC];

        if (in_array($field, $params, true) && in_array(strtoupper($sort), $orderBy, true)) {
            $qb->orderBy('users.'.$field, $sort);
            
        } elseif ( $field !== null && $sort !== null) {
            throw new  \InvalidArgumentException();
        }

        return $qb;
    }
}
