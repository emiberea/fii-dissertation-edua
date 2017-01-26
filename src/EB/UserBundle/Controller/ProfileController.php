<?php

namespace EB\UserBundle\Controller;

use EB\UserBundle\Entity\AbstractUser;
use EB\UserBundle\Entity\SchoolStaffUser;
use EB\UserBundle\Entity\StudentUser;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    /**
     * Method which overrides the method from the base controller from FOSUserBundle. The route is defined in FOSUserBundle.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction()
    {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        if ($user instanceof StudentUser) {
            return $this->redirectToRoute('eb_user_student_profile_show');
        } elseif ($user instanceof SchoolStaffUser) {
            return $this->redirectToRoute('eb_user_school_staff_profile_show');
        } else {
            throw $this->createNotFoundException('Profile page for user not found.');
        }
    }

    /**
     * Method which overrides the method from the base controller from FOSUserBundle. The route is defined in FOSUserBundle.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request)
    {
        /** @var AbstractUser $user */
        $user = $this->getUser();
        if ($user instanceof StudentUser) {
            return $this->redirectToRoute('eb_user_student_profile_edit');
        } elseif ($user instanceof SchoolStaffUser) {
            return $this->redirectToRoute('eb_user_school_staff_profile_edit');
        } else {
            throw $this->createNotFoundException('Profile edit page for user not found.');
        }
    }
}
