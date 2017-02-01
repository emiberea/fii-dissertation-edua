<?php

namespace EB\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EB\CoreBundle\Entity\Admission;

class LoadAdmissionData extends BaseFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= FixtureConfig::MAX_ADMISSIONS; $i++) {
            $admission = new Admission();
            $admission->setBudgetFinancedNo(5);//$this->faker->numberBetween(10, 400));
            $admission->setFeePayerNo(5);//$this->faker->numberBetween(5, 150));
            $admission->setSessionDate($this->faker->dateTimeBetween('-2 years', 'now'));

            $sessionDate = clone $admission->getSessionDate();
            $admission->setClosedAt($sessionDate->add(new \DateInterval('P1M')));
            $admission->setStatus(Admission::STATUS_READY_TO_PROCESS); // all the admissions will be ready for process by default

            $randomSchoolNo = mt_rand(1, count(FixtureConfig::$schoolArr)); // number between 0 and max (0, max]
            $admission->setSchool($this->getReference('school-' . $randomSchoolNo));

            $this->addReference('admission-' . $i, $admission);
            $manager->persist($admission);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
