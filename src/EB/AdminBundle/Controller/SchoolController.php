<?php

namespace EB\AdminBundle\Controller;

use EB\AdminBundle\Form\Type\SchoolType;
use EB\CoreBundle\Entity\School;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/school")
 */
class SchoolController extends Controller
{
    /**
     * Lists all school entities.
     *
     * @Route("/", name="eb_admin_school_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $schools = $em->getRepository('EBCoreBundle:School')->findAll();

        return $this->render('EBAdminBundle:School:index.html.twig', array(
            'schools' => $schools,
        ));
    }

    /**
     * Creates a new school entity.
     *
     * @Route("/new", name="eb_admin_school_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $school = new School();
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($school);
            $em->flush($school);

            $this->addFlash('success', 'New school created!');

            return $this->redirectToRoute('eb_admin_school_show', array('id' => $school->getId()));
        }

        return $this->render('EBAdminBundle:School:new.html.twig', array(
            'school' => $school,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a school entity.
     *
     * @Route("/{id}", name="eb_admin_school_show")
     * @Method("GET")
     */
    public function showAction(School $school)
    {
        $deleteForm = $this->createDeleteForm($school);

        return $this->render('EBAdminBundle:School:show.html.twig', array(
            'school' => $school,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing school entity.
     *
     * @Route("/{id}/edit", name="eb_admin_school_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, School $school)
    {
        $deleteForm = $this->createDeleteForm($school);
        $editForm = $this->createForm(SchoolType::class, $school);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'School edited successfully!');

            return $this->redirectToRoute('eb_admin_school_edit', array('id' => $school->getId()));
        }

        return $this->render('EBAdminBundle:School:edit.html.twig', array(
            'school' => $school,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a school entity.
     *
     * @Route("/{id}", name="eb_admin_school_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, School $school)
    {
        $form = $this->createDeleteForm($school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($school);
            $em->flush($school);

            $this->addFlash('success', 'School deleted successfully!');
        }

        return $this->redirectToRoute('eb_admin_school_index');
    }

    /**
     * Creates a form to delete a school entity.
     *
     * @param School $school The school entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(School $school)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eb_admin_school_delete', array('id' => $school->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
