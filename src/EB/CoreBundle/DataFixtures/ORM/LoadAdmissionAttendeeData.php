<?php

namespace EB\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EB\CoreBundle\Entity\AdmissionAttendee;

class LoadAdmissionAttendeeData extends BaseFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= FixtureConfig::MAX_ADMISSION_ATTENDEES; $i++) {
            $admissionAttendee = new AdmissionAttendee();
            $admissionAttendee->setBaccalaureateAverageGrade($this->faker->numberBetween(6, 10));
            $admissionAttendee->setBaccalaureateMaximumGrade($this->faker->numberBetween(6, 10));

            $randomAdmissionNo = mt_rand(1, FixtureConfig::MAX_ADMISSIONS); // number between 0 and max (0, max]
            $admissionAttendee->setAdmission($this->getReference('admission-' . $randomAdmissionNo));
            $randomStudentNo = mt_rand(1, FixtureConfig::MAX_STUDENTS_USERS); // number between 0 and max (0, max]
            $admissionAttendee->setStudentUser($this->getReference('student-user-' . $randomStudentNo));

            $this->addReference('admission-attendee-' . $i, $admissionAttendee);
            $manager->persist($admissionAttendee);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 6;
    }
}
