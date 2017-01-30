<?php

namespace EB\UserBundle\Controller;

use EB\UserBundle\Entity\AbstractUser;
use EB\UserBundle\Entity\AdminUser;
use EB\UserBundle\Form\Type\AdminProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/profile", name="eb_user_admin_profile_show")
     * @Template()
     */
    public function showAction()
    {
        /** @var AdminUser $admin */
        $admin = $this->getUser();
        if (!($admin instanceof AdminUser)) {
            throw $this->createAccessDeniedException('The logged user is not an AdminUser.');
        }

        return [
            'admin' => $admin,
        ];
    }

    /**
     * @Route("/profile/edit", name="eb_user_admin_profile_edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        /** @var AdminUser $admin */
        $admin = $this->getUser();
        if (!($admin instanceof AdminUser)) {
            throw $this->createAccessDeniedException('The logged user is not an AdminUser.');
        }

        $form = $this->createForm(AdminProfileType::class, $admin);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($admin);
                $em->flush();

                return $this->redirectToRoute('eb_user_admin_profile_edit');
            }
        }

        return [
            'admin' => $admin,
            'form' => $form->createView(),
        ];
    }
}
