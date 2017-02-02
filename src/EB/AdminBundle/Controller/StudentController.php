<?php

namespace EB\AdminBundle\Controller;

use EB\AdminBundle\Form\Type\StudentType;
use EB\UserBundle\Entity\StudentUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/student")
 */
class StudentController extends Controller
{
    /**
     * Lists all studentUser entities.
     *
     * @Route("/", name="eb_admin_student_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $studentUsers = $em->getRepository('EBUserBundle:StudentUser')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $studentUsers,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBAdminBundle:Student:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new studentUser entity.
     *
     * @Route("/new", name="eb_admin_student_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $studentUser = new StudentUser();
        $form = $this->createForm(StudentType::class, $studentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentUser->setPlainPassword(uniqid());
            $studentUser->setEnabled(true);
            $studentUser->addRole('ROLE_STUDENT');
            $studentUser->setPasswordRequestedAt(new \DateTime());
            $studentUser->setConfirmationToken($this->get('fos_user.util.token_generator')->generateToken());

            // send registration (reset password) email to the Student
            $this->get('eb_core.service.mailer')->sendEmail($studentUser->getEmail(), 'EBAdminBundle:Student/Email:newAccount.html.twig', [
                'studentUser' => $studentUser,
                'confirmationUrl' => $this->generateUrl('fos_user_resetting_reset', ['token' => $studentUser->getConfirmationToken()], true),
            ]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($studentUser);
            $em->flush($studentUser);

            $this->addFlash('success', 'New student created!');

            return $this->redirectToRoute('eb_admin_student_show', array('id' => $studentUser->getId()));
        }

        return $this->render('EBAdminBundle:Student:new.html.twig', array(
            'studentUser' => $studentUser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a studentUser entity.
     *
     * @Route("/{id}", name="eb_admin_student_show")
     * @Method("GET")
     */
    public function showAction(StudentUser $studentUser)
    {
        $deleteForm = $this->createDeleteForm($studentUser);

        return $this->render('EBAdminBundle:Student:show.html.twig', array(
            'studentUser' => $studentUser,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing studentUser entity.
     *
     * @Route("/{id}/edit", name="eb_admin_student_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, StudentUser $studentUser)
    {
        $deleteForm = $this->createDeleteForm($studentUser);
        $editForm = $this->createForm(StudentType::class, $studentUser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Student edited successfully!');

            return $this->redirectToRoute('eb_admin_student_edit', array('id' => $studentUser->getId()));
        }

        return $this->render('EBAdminBundle:Student:edit.html.twig', array(
            'studentUser' => $studentUser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a studentUser entity.
     *
     * @Route("/{id}", name="eb_admin_student_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, StudentUser $studentUser)
    {
        $form = $this->createDeleteForm($studentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($studentUser);
            $em->flush($studentUser);

            $this->addFlash('success', 'Student deleted successfully!');
        }

        return $this->redirectToRoute('eb_admin_student_index');
    }

    /**
     * Creates a form to delete a studentUser entity.
     *
     * @param StudentUser $studentUser The studentUser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(StudentUser $studentUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eb_admin_student_delete', array('id' => $studentUser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
