<?php

namespace Klac\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Klac\AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@test.test');
        $admin->setPlainPassword('secret');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setEnabled(true);

        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('user-' . $i);
            $user->setEmail('user' . $i . '@test.test');
            $user->setPlainPassword('secret' . $i);
            $user->setRoles(['ROLE_USER']);
            $user->setEnabled(true);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}