<?php

namespace EB\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RegistrationStudentUserController extends Controller
{
    /**
     * @Route("/register/student", name="eb_user_register_student")
     */
    public function registerAction()
    {
        return $this
            ->get('pugx_multi_user.registration_manager')
            ->register('EB\UserBundle\Entity\StudentUser');
    }
}
