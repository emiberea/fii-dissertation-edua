<?php

namespace EB\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use EB\CoreBundle\Entity\Admission;
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
            ->setName('eb_edua:close_admission_command')
            ->setDescription('Command which closes the Admission and computes the results for all students.');
    }

    /**
     * {@inheritdoc}
     * @todo finish close admission workflow
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $this->input  = $input;
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine')->getManager();

        // take the first Admission with status STATUS_READY_TO_PROCESS
        /** @var Admission $admission */
        $admission = $this->em->getRepository('EBCoreBundle:Admission')->findOneBy([
            'status' => Admission::STATUS_READY_TO_PROCESS,
        ]);

        $admissionAttendees = $admission->getAdmissionAttendees();

        foreach ($admissionAttendees as $admissionAttendee) {
            var_dump($admissionAttendee);
        }

//        var_dump($admission);
    }
}
