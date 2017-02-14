<?php

namespace EB\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EB\UserBundle\Entity\AbstractUser;
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
            $studentUser->setEmail('emi.berea+student-' . $i . '@gmail.com');
            $studentUser->setPlainPassword(FixtureConfig::PASSWORD);
            $studentUser->setEnabled(true);
            $studentUser->setVerified(true);
            $studentUser->addRole('ROLE_STUDENT');
            $studentUser->setFirstName($this->faker->firstName);
            $studentUser->setLastName($this->faker->lastName);
            $studentUser->setTitle(array_rand(AbstractUser::$titleArr));
            $studentUser->setFatherInitial(strtoupper($this->faker->randomLetter));
            $studentUser->setPin($this->faker->randomNumber(8));
            $studentUser->setCity($this->faker->city);
            $studentUser->setAddress($this->faker->address);

            $k = array_rand(FixtureConfig::$highSchoolArr);
            $studentUser->setHighSchool(FixtureConfig::$highSchoolArr[$k]);
            $k = array_rand(FixtureConfig::$specialisationArr);
            $studentUser->setSpecialization(FixtureConfig::$specialisationArr[$k]);

            $studentUser->setBaccalaureateAverageGrade($this->faker->randomNumber(1));
            $studentUser->setBaccalaureateMaximumGrade($this->faker->randomNumber(1));

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
        return 4;
    }
}
