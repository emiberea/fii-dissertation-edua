<?php

namespace EB\CoreBundle\Controller;

use EB\CoreBundle\Entity\Admission;
use EB\CoreBundle\Entity\AdmissionAttendee;
use EB\CoreBundle\Form\Type\AdmissionAttendeeType;
use EB\CoreBundle\Form\Type\AdmissionType;
use EB\UserBundle\Entity\SchoolStaffUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/school-staff")
 */
class SchoolStaffUserController extends Controller
{
    /**
     * @Route("/view-school", name="eb_core_ssu_view_school")
     * @Method("GET")
     */
    public function viewSchoolAction()
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        $school = $schoolStaffUser->getSchool();

        return $this->render('EBCoreBundle:SchoolStaffUser:viewSchool.html.twig', array(
            'schoolStaffUser' => $schoolStaffUser,
            'school' => $school,
        ));
    }

    /**
     * @Route("/admission", name="eb_core_ssu_admission_index")
     * @Method("GET")
     */
    public function admissionIndexAction(Request $request)
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        $school = $schoolStaffUser->getSchool();
        if (!$school) {
            throw $this->createNotFoundException('School not found.');
        }

        $em = $this->getDoctrine()->getManager();
        $admissions = $em->getRepository('EBCoreBundle:Admission')->findBy([
            'school' => $school,
        ]);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $admissions,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBCoreBundle:SchoolStaffUser:admissionIndex.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * @Route("/admission/new", name="eb_core_ssu_admission_new")
     * @Method({"GET", "POST"})
     */
    public function admissionNewAction(Request $request)
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        $school = $schoolStaffUser->getSchool();
        if (!$school) {
            throw $this->createNotFoundException('School not found.');
        }

        $admission = new Admission();
        $form = $this->createForm(AdmissionType::class, $admission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admission->setSchool($school);

            $em = $this->getDoctrine()->getManager();
            $em->persist($admission);
            $em->flush();

            $this->addFlash('success', 'New admission created!');

            return $this->redirectToRoute('eb_core_ssu_admission_show', array('id' => $admission->getId()));
        }

        return $this->render('EBCoreBundle:SchoolStaffUser:admissionNew.html.twig', array(
            'admission' => $admission,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admission/{id}", name="eb_core_ssu_admission_show")
     * @Method("GET")
     */
    public function admissionShowAction(Admission $admission)
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        $school = $schoolStaffUser->getSchool();
        if (!$school) {
            throw $this->createNotFoundException('School not found.');
        }
        if ($school !== $admission->getSchool()) {
            throw $this->createAccessDeniedException('Admission does not belong to your school.');
        }

        $deleteForm = $this->admissionCreateDeleteForm($admission);

        return $this->render('EBCoreBundle:SchoolStaffUser:admissionShow.html.twig', array(
            'admission' => $admission,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/admission/{id}/edit", name="eb_core_ssu_admission_edit")
     * @Method({"GET", "POST"})
     */
    public function admissionEditAction(Request $request, Admission $admission)
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        $school = $schoolStaffUser->getSchool();
        if (!$school) {
            throw $this->createNotFoundException('School not found.');
        }
        if ($school !== $admission->getSchool()) {
            throw $this->createAccessDeniedException('Admission does not belong to your school.');
        }

        $deleteForm = $this->admissionCreateDeleteForm($admission);
        $editForm = $this->createForm(AdmissionType::class, $admission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // @TODO: add some integrity checks that all attending students were verified
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Admission edited successfully!');

            return $this->redirectToRoute('eb_core_ssu_admission_edit', array('id' => $admission->getId()));
        }

        return $this->render('EBCoreBundle:SchoolStaffUser:admissionEdit.html.twig', array(
            'admission' => $admission,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/admission/{id}", name="eb_core_ssu_admission_delete")
     * @Method("DELETE")
     */
    public function admissionDeleteAction(Request $request, Admission $admission)
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        $school = $schoolStaffUser->getSchool();
        if (!$school) {
            throw $this->createNotFoundException('School not found.');
        }
        if ($school !== $admission->getSchool()) {
            throw $this->createAccessDeniedException('Admission does not belong to your school.');
        }

        $form = $this->admissionCreateDeleteForm($admission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admission);
            $em->flush();

            $this->addFlash('success', 'Admission deleted successfully!');
        }

        return $this->redirectToRoute('eb_core_ssu_admission_index');
    }

    /**
     * @return \Symfony\Component\Form\Form The form
     */
    private function admissionCreateDeleteForm(Admission $admission)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eb_core_ssu_admission_delete', array('id' => $admission->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/admission/{id}/students", name="eb_core_ssu_admission_view_students")
     * @Method("GET")
     */
    public function admissionViewStudentsAction(Request $request, Admission $admission)
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        $school = $schoolStaffUser->getSchool();
        if (!$school) {
            throw $this->createNotFoundException('School not found.');
        }
        if ($school !== $admission->getSchool()) {
            throw $this->createAccessDeniedException('Admission does not belong to your school.');
        }

        $em = $this->getDoctrine()->getManager();
        $admissionAttendees = $em->getRepository('EBCoreBundle:AdmissionAttendee')->findBy([
            'admission' => $admission,
        ]);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $admissionAttendees,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBCoreBundle:SchoolStaffUser:admissionViewStudents.html.twig', array(
            'admission' => $admission,
            'pagination' => $pagination,
        ));
    }

    /**
     * @ParamConverter("admission", class="EBCoreBundle:Admission", options={"id"="admissionId"})
     * @ParamConverter("admissionAttendee", class="EBCoreBundle:AdmissionAttendee", options={"id"="admissionAttendeeId"})
     * @Route("/admission/{admissionId}/student/{admissionAttendeeId}", name="eb_core_ssu_admission_edit_student")
     * @Method({"GET", "POST"})
     */
    public function admissionEditStudentAction(Request $request, Admission $admission, AdmissionAttendee $admissionAttendee)
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        $school = $schoolStaffUser->getSchool();
        if (!$school) {
            throw $this->createNotFoundException('School not found.');
        }
        if ($school !== $admission->getSchool()) {
            throw $this->createAccessDeniedException('Admission does not belong to your school.');
        }

        $form = $this->createForm(AdmissionAttendeeType::class, $admissionAttendee, [
            'form_type' => AdmissionAttendeeType::FORM_TYPE_FULL,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admissionAttendee);
            $em->flush();

            $this->addFlash('success', 'Attending student edited successfully!');

            return $this->redirectToRoute('eb_core_ssu_admission_edit_student', [
                'admissionId' => $admission->getId(),
                'admissionAttendeeId' => $admissionAttendee->getId(),
            ]);
        }

        return $this->render('EBCoreBundle:SchoolStaffUser:admissionEditStudent.html.twig', array(
            'admission' => $admission,
            'student' => $admissionAttendee->getStudentUser(),
            'form' => $form->createView(),
        ));
    }
}
