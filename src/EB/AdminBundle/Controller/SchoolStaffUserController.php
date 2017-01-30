<?php

namespace EB\AdminBundle\Controller;

use EB\AdminBundle\Form\Type\SchoolStaffUserType;
use EB\UserBundle\Entity\SchoolStaffUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/school-staff")
 */
class SchoolStaffUserController extends Controller
{
    /**
     * Lists all schoolStaffUser entities.
     *
     * @Route("/", name="eb_admin_ssu_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $schoolStaffUsers = $em->getRepository('EBUserBundle:SchoolStaffUser')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $schoolStaffUsers,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBAdminBundle:SchoolStaffUser:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new schoolStaffUser entity.
     *
     * @Route("/new", name="eb_admin_ssu_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $schoolStaffUser = new SchoolStaffUser();
        $form = $this->createForm(SchoolStaffUserType::class, $schoolStaffUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $schoolStaffUser->setPlainPassword(uniqid());
            $schoolStaffUser->setEnabled(true);
            $schoolStaffUser->addRole('ROLE_SSU');
            $schoolStaffUser->setPasswordRequestedAt(new \DateTime());
            $schoolStaffUser->setConfirmationToken($this->get('fos_user.util.token_generator')->generateToken());

            // send registration (reset password) email to the School Staff User
            $this->get('eb_core.service.mailer')->sendEmail($schoolStaffUser->getEmail(), 'EBAdminBundle:SchoolStaffUser/Email:newAccount.html.twig', [
                'schoolStaffUser' => $schoolStaffUser,
                'confirmationUrl' => $this->generateUrl('fos_user_resetting_reset', ['token' => $schoolStaffUser->getConfirmationToken()], true),
            ]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($schoolStaffUser);
            $em->flush($schoolStaffUser);

            $this->addFlash('success', 'New School Staff User created!');

            return $this->redirectToRoute('eb_admin_ssu_show', array('id' => $schoolStaffUser->getId()));
        }

        return $this->render('EBAdminBundle:SchoolStaffUser:new.html.twig', array(
            'schoolStaffUser' => $schoolStaffUser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a schoolStaffUser entity.
     *
     * @Route("/{id}", name="eb_admin_ssu_show")
     * @Method("GET")
     */
    public function showAction(SchoolStaffUser $schoolStaffUser)
    {
        $deleteForm = $this->createDeleteForm($schoolStaffUser);

        return $this->render('EBAdminBundle:SchoolStaffUser:show.html.twig', array(
            'schoolStaffUser' => $schoolStaffUser,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing schoolStaffUser entity.
     *
     * @Route("/{id}/edit", name="eb_admin_ssu_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SchoolStaffUser $schoolStaffUser)
    {
        $deleteForm = $this->createDeleteForm($schoolStaffUser);
        $editForm = $this->createForm(SchoolStaffUserType::class, $schoolStaffUser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'School Staff User edited successfully!');

            return $this->redirectToRoute('eb_admin_ssu_edit', array('id' => $schoolStaffUser->getId()));
        }

        return $this->render('EBAdminBundle:SchoolStaffUser:edit.html.twig', array(
            'schoolStaffUser' => $schoolStaffUser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a schoolStaffUser entity.
     *
     * @Route("/{id}", name="eb_admin_ssu_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SchoolStaffUser $schoolStaffUser)
    {
        $form = $this->createDeleteForm($schoolStaffUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($schoolStaffUser);
            $em->flush($schoolStaffUser);

            $this->addFlash('success', 'School Staff User deleted successfully!');
        }

        return $this->redirectToRoute('eb_admin_ssu_index');
    }

    /**
     * Creates a form to delete a schoolStaffUser entity.
     *
     * @param SchoolStaffUser $schoolStaffUser The schoolStaffUser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SchoolStaffUser $schoolStaffUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eb_admin_ssu_delete', array('id' => $schoolStaffUser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
