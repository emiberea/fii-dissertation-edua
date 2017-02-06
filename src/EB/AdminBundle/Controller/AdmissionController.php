<?php

namespace EB\AdminBundle\Controller;

use EB\AdminBundle\Form\Type\AdmissionType;
use EB\CoreBundle\Entity\Admission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/admission")
 */
class AdmissionController extends Controller
{
    /**
     * Lists all admission entities.
     *
     * @Route("/", name="eb_admin_admission_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $admissions = $em->getRepository('EBCoreBundle:Admission')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $admissions,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBAdminBundle:Admission:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new admission entity.
     *
     * @Route("/new", name="eb_admin_admission_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $admission = new Admission();
        $form = $this->createForm(AdmissionType::class, $admission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admission);
            $em->flush($admission);

            $this->addFlash('success', 'New admission created!');

            return $this->redirectToRoute('eb_admin_admission_show', array('id' => $admission->getId()));
        }

        return $this->render('EBAdminBundle:Admission:new.html.twig', array(
            'admission' => $admission,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a admission entity.
     *
     * @Route("/{id}", name="eb_admin_admission_show")
     * @Method("GET")
     */
    public function showAction(Admission $admission)
    {
        $deleteForm = $this->createDeleteForm($admission);

        return $this->render('EBAdminBundle:Admission:show.html.twig', array(
            'admission' => $admission,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing admission entity.
     *
     * @Route("/{id}/edit", name="eb_admin_admission_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Admission $admission)
    {
        $deleteForm = $this->createDeleteForm($admission);
        $editForm = $this->createForm(AdmissionType::class, $admission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Admission edited successfully!');

            return $this->redirectToRoute('eb_admin_admission_edit', array('id' => $admission->getId()));
        }

        return $this->render('EBAdminBundle:Admission:edit.html.twig', array(
            'admission' => $admission,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a admission entity.
     *
     * @Route("/{id}", name="eb_admin_admission_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Admission $admission)
    {
        $form = $this->createDeleteForm($admission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admission);
            $em->flush($admission);

            $this->addFlash('success', 'Admission deleted successfully!');
        }

        return $this->redirectToRoute('eb_admin_admission_index');
    }

    /**
     * Creates a form to delete a admission entity.
     *
     * @param Admission $admission The admission entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Admission $admission)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eb_admin_admission_delete', array('id' => $admission->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/{id}/students", name="eb_admin_admission_view_students")
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

        return $this->render('EBAdminBundle:Admission:viewStudents.html.twig', array(
            'admission' => $admission,
            'pagination' => $pagination,
        ));
    }
}
