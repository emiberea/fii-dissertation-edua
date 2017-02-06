<?php

namespace EB\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use EB\CoreBundle\Entity\Admission;
use EB\CoreBundle\Entity\AdmissionAttendee;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CloseAdmissionCommand extends ContainerAwareCommand
{
    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;

    /** @var EntityManager $em */
    private $em;

    protected function configure()
    {
        $this
            ->setName('edua:close-admission')
            ->setDescription('Command which closes the Admission and computes the results for all students.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $this->input  = $input;
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine')->getManager();

        // take the first Admission with status STATUS_READY_TO_PROCESS
        /** @var Admission $admission */
        $admissions = $this->em->getRepository('EBCoreBundle:Admission')->findBy([
            'status' => Admission::STATUS_READY_TO_PROCESS,
        ]);

        foreach ($admissions as $admission) {
            $this->processAdmission($admission);
            $this->computeAdmissionStats($admission);
            $this->updateAdmissionStatus($admission);
        }
    }

    /**
     * @param Admission $admission
     */
    private function processAdmission(Admission $admission)
    {
        $admission->setStatus(Admission::STATUS_PROCESSING);
        $this->em->persist($admission);
        $this->em->flush();

        $batchSize = 5;
        $i = 0;
        $admissionAttendees = $admission->getAdmissionAttendees();
        foreach ($admissionAttendees as $admissionAttendee) {
            if (!$admissionAttendee->isVerified()
                || $admissionAttendee->getBaccalaureateAverageGrade() === null
                || $admissionAttendee->getBaccalaureateMaximumGrade() === null
                || $admissionAttendee->getAdmissionExamGrade() === null
            ) {
                // RESULT_REJECTED_MANUALLY case the student is NOT verified or in case any of the final grade components is missing
                $admissionAttendee->setResult(AdmissionAttendee::RESULT_REJECTED_MANUALLY);
                $this->em->persist($admissionAttendee);

                continue;
            }

            $finalGrade = 0.5 * $admissionAttendee->getAdmissionExamGrade() + 0.25 * $admissionAttendee->getBaccalaureateAverageGrade() + 0.25 * $admissionAttendee->getBaccalaureateMaximumGrade();
            $admissionAttendee->setFinalGrade($finalGrade);

            $this->em->persist($admissionAttendee);

            if (($i % $batchSize) === 0) {
                $this->em->flush(); // Executes all updates.
//                $this->em->clear(); // Detaches all objects from Doctrine!
            }
            ++$i;
        }

        $this->em->flush();
    }

    /**
     * @param Admission $admission
     */
    private function computeAdmissionStats(Admission $admission)
    {
        // get all attendees for this admission ordered by the final grade DESC
        /** @var AdmissionAttendee[] $admissionAttendees */
        $admissionAttendees = $this->em->getRepository('EBCoreBundle:AdmissionAttendee')->findByAdmission($admission);

        // set the status for each attendee based on the number of available slots and their position based on the final grade
        $batchSize = 5;
        $i = 1;
        foreach ($admissionAttendees as $admissionAttendee) {
            if ($i <= $admission->getBudgetFinancedNo()) {
                $admissionAttendee->setResult(AdmissionAttendee::RESULT_ACCEPTED_BUDGET);
            } elseif ($i > $admission->getBudgetFinancedNo() && $i <= ($admission->getBudgetFinancedNo() + $admission->getFeePayerNo())) {
                $admissionAttendee->setResult(AdmissionAttendee::RESULT_ACCEPTED_FEE);
            } else {
                $admissionAttendee->setResult(AdmissionAttendee::RESULT_REJECTED);
            }

            $this->em->persist($admissionAttendee); // Executes all updates.

            if (($i % $batchSize) === 0) {
                $this->em->flush(); // Executes all updates.
//                $this->em->clear(); // Detaches all objects from Doctrine!
            }
            ++$i;
        }

        $this->em->flush();
    }

    /**
     * @param Admission $admission
     */
    private function updateAdmissionStatus(Admission $admission)
    {
        $minBudgetMark = $this->em->getRepository('EBCoreBundle:AdmissionAttendee')
            ->findMinBudgetMarkByAdmissionAndResult($admission, AdmissionAttendee::RESULT_ACCEPTED_BUDGET);
        $minFeeMark = $this->em->getRepository('EBCoreBundle:AdmissionAttendee')
            ->findMinBudgetMarkByAdmissionAndResult($admission, AdmissionAttendee::RESULT_ACCEPTED_FEE);

        $admission->setBudgetFeeThreshold($minBudgetMark);
        $admission->setFeeRejectedThreshold($minFeeMark);
        $admission->setStatus(Admission::STATUS_CLOSED);

        $this->em->persist($admission);
        $this->em->flush();
    }
}
