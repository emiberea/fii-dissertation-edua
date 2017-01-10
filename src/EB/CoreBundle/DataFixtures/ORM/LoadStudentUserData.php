<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EB\UserBundle\Entity\StudentUser;

class LoadStudentUserData extends BaseFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= FixtureConfig::MAX_STUDENTS_USERS; $i++) {
            $studentUser = new StudentUser();
            $studentUser->setUsername('student-' . $i);
            $studentUser->setEmail('emi.berea+student' . $i . '@gmail.com');
            $studentUser->setPlainPassword('12345');
            $studentUser->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
            $studentUser->setEnabled(true);
            $studentUser->setFirstName($this->getFaker()->firstName);
            $studentUser->setLastName($this->getFaker()->lastName);
            $studentUser->setFatherInitial(strtoupper($this->getFaker()->randomLetter));
            $studentUser->setPin($this->getFaker()->randomNumber(8));
            $studentUser->setCity($this->getFaker()->city);
            $studentUser->setAddress($this->getFaker()->address);

            $k = array_rand(FixtureConfig::$highSchoolArr);
            $studentUser->setHighSchool(FixtureConfig::$highSchoolArr[$k]);
            $k = array_rand(FixtureConfig::$specialisationArr);
            $studentUser->setSpecialization(FixtureConfig::$specialisationArr[$k]);

            $studentUser->setBaccalaureateAverageGrade($this->getFaker()->randomNumber(1));
            $studentUser->setBaccalaureateMaximumGrade($this->getFaker()->randomNumber(1));

            $this->addReference('student-user-' . $i, $studentUser);
            $manager->persist($studentUser);
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
