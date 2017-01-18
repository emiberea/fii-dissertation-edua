<?php

namespace EB\UserBundle\EventListener;

use EB\CoreBundle\Event\NotificationEvent;
use EB\CoreBundle\Event\NotificationEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
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

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationConfirmed',
        ];
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
