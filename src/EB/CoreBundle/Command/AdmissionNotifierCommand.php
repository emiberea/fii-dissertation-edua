<?php

namespace EB\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use EB\CoreBundle\Entity\Admission;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AdmissionNotifierCommand extends ContainerAwareCommand
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
            ->setName('edua:admission-notifier')
            ->setDescription('Command which notifies all Ssu users when an admission is about to close.');
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

        // take the admissions have the status open and have just 1 day before the closedAt date is reached
        /** @var Admission $admission */
        $admissions = $this->em->getRepository('EBCoreBundle:Admission')->findByClosedAtAndDayOffset(1);
        foreach ($admissions as $admission) {
            $this->notifySsu($admission);
        }
    }

    /**
     * @param Admission $admission
     * @todo do a dispatch to an event and handle in NotificationListener
     */
    private function notifySsu(Admission $admission)
    {
        $schoolStaffUsers = $admission->getSchool()->getSchoolStaffUsers();
        foreach ($schoolStaffUsers as $schoolStaffUser) {
            // notify ssu
        }

    }
}
