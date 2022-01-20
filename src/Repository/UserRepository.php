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
    public function findByFilter(?array $filter): array
    {
        $filter ??= [];
        $qb = $this->createQueryBuilder('users');

        $conditions = $qb->expr()->orX();

        if ($role = $filter['role'] ?? null) {
            $conditions->add("users.roles LIKE :role");
            $qb->setParameter('role', '%"'.$role.'"%');
        }

        if ($conditions->count()) {
            $qb->andWhere($conditions);
        }

        return $qb->getQuery()->execute();
    }

    // for multiple select (UserFilterType-> multiple=> true)

    // /**
    //  * @return array
    //  */
    // public function findUsersByRoles($roles): array
    // {
    //     if ($roles[0] === 'ROLE_USER') {
    //         $roles = [0 => 'ROLE_%'];
    //     }


    //     $rolesString = '["'.implode('"],["', $roles).'"]';
    //     $correctRoles = explode(",", $rolesString);
    //     $qb = $this->createQueryBuilder('users');

    //     $conditions = [];
    //     foreach ($correctRoles as $index => $role) {
    //         $conditions[] = "users.roles LIKE :role$index";
    //         $qb->setParameter("role$index", $role);
    //     }

    //     if (empty($conditions)) {
    //         throw new \LogicException('Conditions are empty.');
    //     }

    //     $qb->Where(new Orx($conditions));

    //     return $qb->getQuery()->execute();
    // }
}
