<?php

namespace EB\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use EB\CoreBundle\Entity\Notification;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Notification controller.
 *
 * @Route("/notification")
 */
class NotificationController extends Controller
{
    /**
     * @Route("/", name="eb_core_notification")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $userId = $this->getUser()->getId();

        $dql = "SELECT n FROM EBCoreBundle:Notification n
                WHERE n.receiverUser = '$userId'
                ORDER BY n.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            10
        );

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @Route("/{id}/redirect", name="eb_core_notification_redirect")
     */
    public function redirectAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Notification $notification */
        $notification = $em->getRepository('EBCoreBundle:Notification')->findOneBy(array(
            'id' => $id,
            'receiverUser' => $this->getUser(),
        ));

        if (!$notification) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        // setting the notification as read
        if ($notification->getIsRead() == false) {
            $notification->setIsRead(true);
            $em->persist($notification);
            $em->flush();
        }

        return $this->redirect($notification->getUrl1());
    }

    /**
     * @Route("/{id}/read", name="eb_core_notification_read")
     */
    public function readAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Notification $notification */
        $notification = $em->getRepository('EBCoreBundle:Notification')->findOneBy(array(
            'id' => $id,
            'receiverUser' => $this->getUser(),
        ));

        if (!$notification) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        // setting the notification as read
        if ($notification->getIsRead() == false) {
            $notification->setIsRead(true);
            $em->persist($notification);
            $em->flush();
        }

        return new Response('notification-read-ok');
    }

    /**
     * @Route("/{id}/delete", name="eb_core_notification_delete")
     */
    public function deleteAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Notification $notification */
        $notification = $em->getRepository('EBCoreBundle:Notification')->findOneBy(array(
            'id' => $id,
            'receiverUser' => $this->getUser(),
        ));

        if (!$notification) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $em->remove($notification);
        $em->flush();

        return new Response('notification-delete-ok');
    }
}
