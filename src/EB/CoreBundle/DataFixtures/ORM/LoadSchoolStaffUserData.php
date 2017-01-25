<?php

namespace EB\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EB\UserBundle\Entity\SchoolStaffUser;

class LoadSchoolStaffUserData extends BaseFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= FixtureConfig::MAX_SCHOOL_STAFF_USERS; $i++) {
            $schoolStaffUser = new SchoolStaffUser();
            $schoolStaffUser->setEmail('emi.berea+ssu-' . $i . '@gmail.com');
            $schoolStaffUser->setPlainPassword(FixtureConfig::PASSWORD);
            $schoolStaffUser->setEnabled(true);
            $schoolStaffUser->setFirstName($this->faker->firstName);
            $schoolStaffUser->setLastName($this->faker->lastName);
            $schoolStaffUser->setTitle($this->faker->title);
            $schoolStaffUser->setJobTitle($this->faker->jobTitle);
            $schoolStaffUser->setAcademicDegree($this->faker->text(20));

            $randomSchoolNo = mt_rand(1, count(FixtureConfig::$schoolArr)); // number between 0 and max (0, max]
            $schoolStaffUser->setSchool($this->getReference('school-' . $randomSchoolNo));

            $this->addReference('school-staff-user-' . $i, $schoolStaffUser);
            $manager->persist($schoolStaffUser);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
