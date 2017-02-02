<?php

namespace EB\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use EB\CoreBundle\Entity\Admission;
use EB\CoreBundle\Entity\AdmissionAttendee;
use EB\CoreBundle\Entity\Notification;
use EB\CoreBundle\Event\NotificationEvent;
use EB\CoreBundle\Event\NotificationEvents;
use EB\CoreBundle\Service\MailerService;
use EB\UserBundle\Entity\SchoolStaffUser;
use EB\UserBundle\Entity\StudentUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;

class NotificationListener implements EventSubscriberInterface
{
    /** @var EntityManager $em */
    protected $em;

    /** @var Router $router */
    protected $router;

    /** @var MailerService $mailer */
    protected $mailer;

    /** @var string $mainDomain */
    protected $mainDomain;

    /**
     * @param EntityManager $em
     * @param Router $router
     * @param MailerService $mailer
     * @param $mainDomain
     */
    public function __construct(EntityManager $em, Router $router, MailerService $mailer, $mainDomain)
    {
        $this->em = $em;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->mainDomain = $mainDomain;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            NotificationEvents::STUDENT_REGISTERED => 'onStudentRegistered',
            NotificationEvents::STUDENT_ATTEND_ADMISSION => 'onStudentAttendAdmission',
            NotificationEvents::SSU_CONFIRM_STUDENT => 'onSsuConfirmStudent',
            NotificationEvents::SSU_PRE_CLOSE_ADMISSION => 'onSsuPreCloseAdmission',
        ];
    }

    /**
     * @param NotificationEvent $event
     */
    public function onStudentRegistered(NotificationEvent $event)
    {
        // send notification and email to all admin users
        /** @var StudentUser $student */
        $student = $event->get('student');
        $url1 = $this->router->generate('eb_admin_student_edit', ['id' => $student->getId()], true);

        $admins = $this->em->getRepository('EBUserBundle:AdminUser')->findAll();
        foreach ($admins as $admin) {
            $this->mailer->sendEmail($admin->getEmail(), 'EBCoreBundle:Email:studentRegistered.html.twig', [
                'student' => $student,
                'admin' => $admin,
                'url1' => $url1,
            ]);

            $notification = new Notification();
            $notification
                ->setType(Notification::TYPE_STUDENT_REGISTERED)
                ->setSenderUser($student)
                ->setReceiverUser($admin)
                ->setUrl1($url1)
            ;

            $this->em->persist($notification);
        }

        $this->em->flush();
    }

    /**
     * @param NotificationEvent $event
     */
    public function onStudentAttendAdmission(NotificationEvent $event)
    {
        // send notification and email to all Ssu (school staff users) that are assigned to the attended school
        /** @var AdmissionAttendee $admissionAttendee */
        $admissionAttendee = $event->get('admissionAttendee');
        /** @var StudentUser $student */
        $student = $admissionAttendee->getStudentUser();
        /** @var Admission $admission */
        $admission = $admissionAttendee->getAdmission();
        $url1 = $this->router->generate('eb_core_ssu_admission_edit_student',
            [
                'admissionId' => $admission->getId(),
                'admissionAttendeeId' => $admissionAttendee->getId(),
            ],
            true
        );

        $schoolStaffUsers = $this->em->getRepository('EBUserBundle:SchoolStaffUser')->findBy([
            'school' => $admission->getSchool(),
        ]);
        foreach ($schoolStaffUsers as $schoolStaffUser) {
            $this->mailer->sendEmail($schoolStaffUser->getEmail(), 'EBCoreBundle:Email:studentAttendAdmission.html.twig', [
                'student' => $student,
                'schoolStaffUser' => $schoolStaffUser,
                'admission' => $admission,
                'url1' => $url1,
            ]);

            $notification = new Notification();
            $notification
                ->setType(Notification::TYPE_STUDENT_ATTEND_ADMISSION)
                ->setSenderUser($student)
                ->setReceiverUser($schoolStaffUser)
                ->setUrl1($url1)
            ;

            $this->em->persist($notification);
        }

        $this->em->flush();
    }

    /**
     * @param NotificationEvent $event
     * @todo test this method to be sure it works as intended
     */
    public function onSsuConfirmStudent(NotificationEvent $event)
    {
        // send notification and email to the student, that he was accepted by a Ssu
        /** @var StudentUser $student */
        $student = $event->get('student');
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $event->get('schoolStaffUser');
        /** @var Admission $admission */
        $admission = $event->get('admission');

        $this->mailer->sendEmail($schoolStaffUser->getEmail(), 'EBCoreBundle:Email:ssuConfirmStudent.html.twig', [
            'student' => $student,
            'schoolStaffUser' => $schoolStaffUser,
            'admission' => $admission,
        ]);

        $notification = new Notification();
        $notification
            ->setType(Notification::TYPE_SSU_CONFIRM_STUDENT)
            ->setSenderUser($schoolStaffUser)
            ->setReceiverUser($student)
        ;

        $this->em->persist($notification);
        $this->em->flush();
    }

    /**
     * @param NotificationEvent $event
     */
    public function onSsuPreCloseAdmission(NotificationEvent $event)
    {
        // send notification and email to the Ssu, that in 1 day the admission will be closed
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $event->get('schoolStaffUser');
        /** @var Admission $admission */
        $admission = $event->get('admission');

        // concatenated with 'main_domain' parameter as this event is triggered from the CLI
        $url1 = $this->mainDomain . $this->router->generate('eb_core_ssu_admission_edit', ['id' => $admission->getId()]);

        $this->mailer->sendEmail($schoolStaffUser->getEmail(), 'EBCoreBundle:Email:ssuPreCloseAdmission.html.twig', [
            'schoolStaffUser' => $schoolStaffUser,
            'admission' => $admission,
            'url1' => $url1,
        ]);

        $notification = new Notification();
        $notification
            ->setType(Notification::TYPE_PRE_CLOSE_ADMISSION)
            ->setSenderUser(null) // this notification is generated by the system
            ->setReceiverUser($schoolStaffUser)
            ->setUrl1($url1)
        ;

        $this->em->persist($notification);
        $this->em->flush();
    }
}
