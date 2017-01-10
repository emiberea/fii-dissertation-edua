<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EB\CoreBundle\Entity\School;
use EB\UserBundle\Entity\SchoolStaffUser;

class LoadSchoolData extends BaseFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (FixtureConfig::$schoolArr as $key => $value) {
            $school = new School();
            $school->setName($value['name']);
            $school->setCountry($value['country']);
            $school->setCity($value['city']);
            $school->setAddress($value['address']);
            $school->setType($value['type']);

            $schoolKey = $key + 1;
            $this->addReference('school-' . $schoolKey, $school);
            $manager->persist($school);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
