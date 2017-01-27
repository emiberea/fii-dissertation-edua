<?php

namespace EB\UserBundle\EventListener;

use EB\CoreBundle\Event\NotificationEvent;
use EB\CoreBundle\Event\NotificationEvents;
use EB\UserBundle\Entity\AbstractUser;
use EB\UserBundle\Entity\AdminUser;
use EB\UserBundle\Entity\SchoolStaffUser;
use EB\UserBundle\Entity\StudentUser;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserListener implements EventSubscriberInterface
{
    /** @var EventDispatcherInterface $ed */
    protected $ed;

    /**
     * @param EventDispatcherInterface $ed
     */
    public function __construct(EventDispatcherInterface $ed)
    {
        $this->ed = $ed;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationConfirmed',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $form = $event->getForm();

        /** @var AbstractUser $user */
        $user = $form->getData();
        if ($user instanceof AdminUser) {
            $user->addRole('ROLE_ADMIN');
        } elseif ($user instanceof SchoolStaffUser) {
            $user->addRole('ROLE_SSU');
        } elseif ($user instanceof StudentUser) {
            $user->addRole('ROLE_STUDENT');
        }
    }

    /**
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationConfirmed(FilterUserResponseEvent $event)
    {
        $this->ed->dispatch(NotificationEvents::STUDENT_REGISTERED, new NotificationEvent([
            'student' => $event->getUser(),
        ]));
    }
}
