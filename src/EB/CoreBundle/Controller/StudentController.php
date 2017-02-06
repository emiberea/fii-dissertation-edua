<?php

namespace EB\CoreBundle\Controller;

use EB\CoreBundle\Entity\Admission;
use EB\CoreBundle\Entity\AdmissionAttendee;
use EB\CoreBundle\Event\NotificationEvent;
use EB\CoreBundle\Event\NotificationEvents;
use EB\CoreBundle\Form\Type\AdmissionAttendeeType;
use EB\UserBundle\Entity\StudentUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/student")
 */
class StudentController extends Controller
{
    /**
     * @Route("/open-admissions", name="eb_core_student_open_admissions")
     * @Method("GET")
     */
    public function openAdmissionsAction(Request $request)
    {
        /** @var StudentUser $student */
        $student = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $admissions = $em->getRepository('EBCoreBundle:Admission')->findBy([
            'status' => Admission::STATUS_OPEN,
        ]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $admissions,
            $request->query->getInt('page', 1),
            10
        );

        $admissionAttendeeArr = [];
        foreach ($student->getAdmissionAttendees() as $admissionAttendee) {
            $admissionAttendeeArr[] = $admissionAttendee->getAdmission()->getId();
        }

        return $this->render('EBCoreBundle:Student:openAdmissions.html.twig', array(
            'pagination' => $pagination,
            'admissionAttendeeArr' => $admissionAttendeeArr,
        ));
    }

    /**
     * @Route("/admission/{id}/attend", name="eb_core_student_attend_admission")
     * @Method({"GET", "POST"})
     */
    public function attendAdmissionAction(Request $request, Admission $admission)
    {
        /** @var StudentUser $student */
        $student = $this->getUser();

        $admissionAttendee = new AdmissionAttendee();
        $form = $this->createForm(AdmissionAttendeeType::class, $admissionAttendee, [
            'form_type' => AdmissionAttendeeType::FORM_TYPE_PARTIAL,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $student->isVerified()) {
            $admissionAttendee->setAdmission($admission);
            $admissionAttendee->setStudentUser($student);

            $em = $this->getDoctrine()->getManager();
            $em->persist($admissionAttendee);
            $em->flush();

            $this->get('event_dispatcher')->dispatch(NotificationEvents::STUDENT_ATTEND_ADMISSION, new NotificationEvent([
                'admissionAttendee' => $admissionAttendee,
            ]));

            $this->addFlash('success', 'You have attended the admission successfully!');

            return $this->redirectToRoute('eb_core_student_attended_admissions');
        }

        return $this->render('EBCoreBundle:Student:attendAdmission.html.twig', array(
            'student' => $student,
            'admission' => $admission,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/attended-admissions", name="eb_core_student_attended_admissions")
     * @Method("GET")
     */
    public function attendedAdmissionsAction(Request $request)
    {
        /** @var StudentUser $student */
        $student = $this->getUser();
        $admissionAttendees = $student->getAdmissionAttendees();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $admissionAttendees,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBCoreBundle:Student:attendedAdmissions.html.twig', array(
            'student' => $student,
            'pagination' => $pagination,
        ));
    }

    /**
     * @Route("/admission/{id}/view-attended", name="eb_core_student_view_attended_admission")
     * @Method("GET")
     */
    public function viewAttendedAdmissionAction(Admission $admission)
    {
        /** @var StudentUser $student */
        $student = $this->getUser();

        return $this->render('EBCoreBundle:Student:viewAttendedAdmission.html.twig', array(
            'student' => $student,
            'admission' => $admission,
        ));
    }
}
