<?php

namespace EB\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseController
{
    /**
     * Method which overrides the method from the base controller from FOSUserBundle. The route is defined in FOSUserBundle.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
        // redirect to the Student route, as the Student is the single type of user that needs public registration
        return $this->redirectToRoute('eb_user_register_student');
    }

    /**
     * @Route("/register/student", name="eb_user_register_student")
     */
    public function registerStudentAction()
    {
        return $this
            ->get('pugx_multi_user.registration_manager')
            ->register('EB\UserBundle\Entity\StudentUser');
    }
}
