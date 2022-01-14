<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Orx;
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

    /**
     * @return array
     */
    public function findUsersByRoles($roles): array
    {
        if ($roles[0] === 'ROLE_USER') {
            $roles = [0 => 'ROLE_%'];
        }

        $rolesString = '["'.implode('"],["', $roles).'"]';
        $correctRoles = explode(",", $rolesString);
        $qb = $this->createQueryBuilder('users');

        $conditions = [];
        foreach ($correctRoles as $index => $role) {
            $conditions[] = "users.roles LIKE :role$index";
            $qb->setParameter("role$index", $role);
        }

        if (empty($conditions)) {
            throw new \LogicException('Conditions are empty.');
        }

        $qb->Where(new Orx($conditions));

        return $qb->getQuery()->execute();
    }
}
