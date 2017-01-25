<?php

namespace EB\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EB\UserBundle\Entity\AdminUser;

class LoadAdminUserData extends BaseFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= FixtureConfig::MAX_ADMIN_USERS; $i++) {
            $adminUser = new AdminUser();
            $adminUser->setEmail('emi.berea+admin' . $i . '@gmail.com');
            $adminUser->setPlainPassword(FixtureConfig::PASSWORD);
            $adminUser->setEnabled(true);
            $adminUser->addRole('ROLE_ADMIN');
            $adminUser->setFirstName($this->faker->firstName);
            $adminUser->setLastName($this->faker->lastName);

            $this->addReference('admin-user-' . $i, $adminUser);
            $manager->persist($adminUser);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
