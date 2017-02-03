<?php

namespace EB\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use EB\CoreBundle\Entity\Admission;
use EB\CoreBundle\Event\NotificationEvent;
use EB\CoreBundle\Event\NotificationEvents;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AdmissionNotifierCommand extends ContainerAwareCommand
{
    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;

    /** @var EntityManager $em */
    private $em;

    /** @var EventDispatcherInterface $ed */
    private $ed;

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
        $this->ed = $this->getContainer()->get('event_dispatcher');

        // take the admissions have the status open and have just 1 day before the closedAt date is reached
        /** @var Admission $admission */
        $admissions = $this->em->getRepository('EBCoreBundle:Admission')->findByStatusAndClosedAtDayOffset(Admission::STATUS_OPEN, 1);
        foreach ($admissions as $admission) {
            $this->notifySsu($admission);
        }
    }

    /**
     * @param Admission $admission
     */
    private function notifySsu(Admission $admission)
    {
        // foreach given admission that is about to expire, notify all Ssu
        $schoolStaffUsers = $admission->getSchool()->getSchoolStaffUsers();
        foreach ($schoolStaffUsers as $schoolStaffUser) {
            $this->ed->dispatch(NotificationEvents::SSU_PRE_CLOSE_ADMISSION, new NotificationEvent([
                'schoolStaffUser' => $schoolStaffUser,
                'admission' => $admission,
            ]));
        }
    }
}
