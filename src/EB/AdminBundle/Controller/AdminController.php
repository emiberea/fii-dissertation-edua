<?php

namespace EB\AdminBundle\Controller;

use EB\AdminBundle\Form\Type\AdminType;
use EB\UserBundle\Entity\AdminUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/admin")
 */
class AdminController extends Controller
{
    /**
     * Lists all admin entities.
     *
     * @Route("/", name="eb_admin_admin_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $admins = $em->getRepository('EBUserBundle:AdminUser')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $admins,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('EBAdminBundle:Admin:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new admin entity.
     *
     * @Route("/new", name="eb_admin_admin_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $admin = new AdminUser();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setPlainPassword(uniqid());
            $admin->setEnabled(true);
            $admin->addRole('ROLE_ADMIN');
            $admin->setPasswordRequestedAt(new \DateTime());
            $admin->setConfirmationToken($this->get('fos_user.util.token_generator')->generateToken());

            // send registration (reset password) email to the Admin
            $this->get('eb_core.service.mailer')->sendEmail($admin->getEmail(), 'EBAdminBundle:Admin/Email:newAccount.html.twig', [
                'admin' => $admin,
                'confirmationUrl' => $this->generateUrl('fos_user_resetting_reset', ['token' => $admin->getConfirmationToken()], true),
            ]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush($admin);

            $this->addFlash('success', 'New student created!');

            return $this->redirectToRoute('eb_admin_admin_show', array('id' => $admin->getId()));
        }

        return $this->render('EBAdminBundle:Admin:new.html.twig', array(
            'admin' => $admin,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a admin entity.
     *
     * @Route("/{id}", name="eb_admin_admin_show")
     * @Method("GET")
     */
    public function showAction(AdminUser $admin)
    {
        $deleteForm = $this->createDeleteForm($admin);

        return $this->render('EBAdminBundle:Admin:show.html.twig', array(
            'admin' => $admin,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing admin entity.
     *
     * @Route("/{id}/edit", name="eb_admin_admin_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AdminUser $admin)
    {
        $deleteForm = $this->createDeleteForm($admin);
        $editForm = $this->createForm(AdminType::class, $admin);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Admin edited successfully!');

            return $this->redirectToRoute('eb_admin_admin_edit', array('id' => $admin->getId()));
        }

        return $this->render('EBAdminBundle:Admin:edit.html.twig', array(
            'admin' => $admin,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a admin entity.
     *
     * @Route("/{id}", name="eb_admin_admin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AdminUser $admin)
    {
        $form = $this->createDeleteForm($admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admin);
            $em->flush($admin);

            $this->addFlash('success', 'Admin deleted successfully!');
        }

        return $this->redirectToRoute('eb_admin_admin_index');
    }

    /**
     * Creates a form to delete a admin entity.
     *
     * @param AdminUser $admin The admin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminUser $admin)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eb_admin_admin_delete', array('id' => $admin->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
