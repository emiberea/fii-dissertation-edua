<?php

namespace EB\CoreBundle\Controller;

use EB\CoreBundle\Entity\Admission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admission")
 */
class AdmissionController extends Controller
{
    /**
     * Lists all admission entities.
     *
     * @Route("/", name="eb_core_admission_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $admissions = $em->getRepository('EBCoreBundle:Admission')->findBy(
            ['status' => Admission::STATUS_CLOSED],
            ['sessionDate' => 'DESC']
        );

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $admissions,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBCoreBundle:Admission:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Finds and displays a admission entity.
     *
     * @Route("/{id}", name="eb_core_admission_show")
     * @Method("GET")
     */
    public function showAction(Admission $admission)
    {
        return $this->render('EBCoreBundle:Admission:show.html.twig', array(
            'admission' => $admission,
        ));
    }

    /**
     * @Route("/{id}/students", name="eb_core_admission_view_students")
     * @Method("GET")
     */
    public function viewStudentsAction(Request $request, Admission $admission)
    {
        $em = $this->getDoctrine()->getManager();
        $admissionAttendees = $em->getRepository('EBCoreBundle:AdmissionAttendee')->findBy(
            ['admission' => $admission],
            ['finalGrade' => 'DESC']
        );

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $admissionAttendees,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBCoreBundle:Admission:viewStudents.html.twig', array(
            'admission' => $admission,
            'pagination' => $pagination,
        ));
    }
}
