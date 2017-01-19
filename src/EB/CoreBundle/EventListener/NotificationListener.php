<?php

namespace EB\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use EB\CoreBundle\Entity\Admission;
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

    /**
     * @param EntityManager $em
     * @param Router $router
     * @param MailerService $mailer
     */
    public function __construct(EntityManager $em, Router $router, MailerService $mailer)
    {
        $this->em = $em;
        $this->router = $router;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            NotificationEvents::STUDENT_REGISTERED     => 'onStudentRegistered',
            NotificationEvents::STUDENT_ATTEND_ADMISSION => 'onStudentAttendAdmission',
            NotificationEvents::SSU_CONFIRM_STUDENT => 'onSsuConfirmStudent',
        ];
    }

    /**
     * @param NotificationEvent $event
     * @todo test this method to be sure it works as intended
     */
    public function onStudentRegistered(NotificationEvent $event)
    {
        // send notification and email to all admin users
        /** @var StudentUser $student */
        $student = $event->get('student');

        $admins = $this->em->getRepository('EBUserBundle:AdminUser')->findAll();
        foreach ($admins as $admin) {
            $this->mailer->sendEmail($admin->getEmail(), 'EBCoreBundle:Email:studentRegistered.html.twig' , [
                'student' => $student,
                'admin' => $admin,
            ]);

            $notification = new Notification();
            $notification
                ->setType(Notification::TYPE_STUDENT_REGISTERED)
                ->setSenderUser($student)
                ->setReceiverUser($admin)
            ;

            $this->em->persist($notification);
        }

        $this->em->flush();
    }

    /**
     * @param NotificationEvent $event
     * @todo test this method to be sure it works as intended
     */
    public function onStudentAttendAdmission(NotificationEvent $event)
    {
        // send notification and email to all Ssu (school staff users) that are assigned to that school,
        /** @var StudentUser $student */
        $student = $event->get('student');
        /** @var Admission $admission */
        $admission = $event->get('admission');

        $schoolStaffUsers = $this->em->getRepository('EBUserBundle:SchoolStaffUser')->findBy([
            'school' => $admission->getSchool(),
        ]);
        foreach ($schoolStaffUsers as $schoolStaffUser) {
            $this->mailer->sendEmail($schoolStaffUser->getEmail(), 'EBCoreBundle:Email:studentAttendAdmission.html.twig' , [
                'student' => $student,
                'schoolStaffUser' => $schoolStaffUser,
                'admission' => $admission,
            ]);

            $notification = new Notification();
            $notification
                ->setType(Notification::TYPE_STUDENT_ATTEND_ADMISSION)
                ->setSenderUser($student)
                ->setReceiverUser($schoolStaffUser)
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

        $this->mailer->sendEmail($schoolStaffUser->getEmail(), 'EBCoreBundle:Email:ssuConfirmStudent.html.twig' , [
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
}
