<?php

namespace Klac\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param string $role
     *
     * @return mixed
     */
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u.name, u.email, u.id, u.username')
            ->from('UserBundle:User', 'u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"'.$role.'"%');

        return $qb->getQuery();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getAllUsersQuery()
    {
        return $this->createQueryBuilder('u')->getQuery();
    }
}