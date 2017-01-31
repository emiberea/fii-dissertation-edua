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
            $admission->setBudgetFinancedNo($this->faker->numberBetween(10, 400));
            $admission->setFeePayerNo($this->faker->numberBetween(5, 150));
            if ($i >= 1 && $i <= 12) {
                $admission->setSessionDate($this->faker->dateTimeBetween('-5 years', '-1 years'));
                $admission->setStatus(Admission::STATUS_CLOSED);
            } elseif ($i >= 13 && $i <= 24) {
                $admission->setSessionDate($this->faker->dateTimeBetween('now', '+1 years'));
                $admission->setStatus(Admission::STATUS_READY_TO_PROCESS);
            } elseif ($i >= 25 && $i <= 30) {
                $admission->setSessionDate($this->faker->dateTimeBetween('+1 years', '+5 years'));
                $admission->setStatus(Admission::STATUS_OPEN);
            } else {
                $admission->setSessionDate($this->faker->dateTimeBetween('now', '+5 years'));
                $admission->setStatus(array_rand(Admission::$statusArr));
            }

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
