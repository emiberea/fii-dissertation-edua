<?php

namespace EB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RegistrationSchoolStaffUserController extends Controller
{
    /**
     * @Route("/register/school-staff", name="eb_user_register_school_staff")
     */
    public function registerAction()
    {
        return $this
            ->get('pugx_multi_user.registration_manager')
            ->register('EB\UserBundle\Entity\SchoolStaffUser');
    }
}
