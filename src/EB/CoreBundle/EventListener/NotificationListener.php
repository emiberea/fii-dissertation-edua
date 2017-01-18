<?php

namespace EB\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use EB\CoreBundle\Event\NotificationEvent;
use EB\CoreBundle\Event\NotificationEvents;
use EB\CoreBundle\Service\MailerService;
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
     * NotificationListener constructor.
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

    public function onStudentRegistered(NotificationEvent $event)
    {
        // TODO: implement stuff
    }

    public function onStudentAttendAdmission(NotificationEvent $event)
    {
    }

    public function onSsuConfirmStudent(NotificationEvent $event)
    {
    }
}
