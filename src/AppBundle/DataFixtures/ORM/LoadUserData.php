<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin
            ->setUsername('admin')
            ->setEmail('admin@localhost.de')
            ->setPlainPassword('123')
            ->setEnabled(true)
            ->setRoles(array('ROLE_ADMIN'));

        $manager->persist($userAdmin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
