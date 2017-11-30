<?php

namespace Klac\AppBundle\DataManager;

use Klac\AppBundle\Entity\User;
use Klac\CoreBundle\DataManager\EntityDataManager;

class UserDataManager extends EntityDataManager
{
    const ENTITY_NAME = 'KlacAppBundle:User';

    /**
     * @return array|User[]
     */
    public function getAllUsers()
    {
        $users = $this->repository->findAll();

        return $users;
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getAllUsersQuery()
    {
        $query = $this->repository->getAllUsersQuery();

        return $query;
    }

    /**
     * @param $username
     * @return null|User
     */
    public function getUserByUsername($username)
    {
        $user = $this->repository
            ->findOneBy([
                'username' => $username
            ]);

        return $user;
    }
}