<?php

namespace AppBundle\DataFixtures\ORM;

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
            $schoolStaffUser->setUsername('school-staff-' . $i);
            $schoolStaffUser->setEmail('emi.berea+school-staff' . $i . '@gmail.com');
            $schoolStaffUser->setPlainPassword('12345');
            $schoolStaffUser->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
            $schoolStaffUser->setEnabled(true);
            $schoolStaffUser->setFirstName($this->getFaker()->firstName);
            $schoolStaffUser->setLastName($this->getFaker()->lastName);
            $schoolStaffUser->setTitle($this->getFaker()->title);
            $schoolStaffUser->setJobTitle($this->getFaker()->jobTitle);
            $schoolStaffUser->setAcademicDegree($this->getFaker()->text(20));

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
