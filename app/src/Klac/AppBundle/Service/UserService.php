<?php

namespace Klac\AppBundle\Service;

use Klac\AppBundle\DataManager\UserDataManager;
use Klac\AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserService
{
    /**
     * @var UserDataManager
     */
    protected $dataManager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * UserService constructor
     *
     * @param UserDataManager $dataManager
     * @param ContainerInterface $container
     */
    public function __construct(UserDataManager $dataManager, ContainerInterface $container)
    {
        $this->dataManager = $dataManager;
        $this->container = $container;
    }

    /**
     * @return array|\Klac\AppBundle\Entity\User[]
     */
    public function getUsers()
    {
        $users = $this->dataManager->getAllUsers();

        return $users;
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getUsersQuery()
    {
        $users = $this->dataManager->getAllUsersQuery();

        return $users;
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function loadUserByUsername($username)
    {
        $user = $this->dataManager->getUserByUsername($username);

        return $user;
    }

    /**
     * @param User $user
     */
    public function saveUser(User $user)
    {
        $user->setEnabled(true);
        $user->setRoles(['ROLE_USER']);
        $this->updateUser($user);
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user, true);
    }

    /**
     * @param User $user
     */
    public function deleteUser(User $user)
    {
        $this->dataManager->delete($user);
    }
}