<?php

namespace EB\UserBundle\Controller;

use EB\UserBundle\Entity\SchoolStaffUser;
use EB\UserBundle\Form\Type\SchoolStaffProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/school-staff")
 */
class SchoolStaffProfileController extends Controller
{
    /**
     * @Route("/profile", name="eb_user_school_staff_profile_show")
     * @Template()
     */
    public function showAction()
    {
        /** @var SchoolStaffUser $student */
        $schoolStaffUser = $this->getUser();
        if (!($schoolStaffUser instanceof SchoolStaffUser)) {
            return $this->createAccessDeniedException('The logged user is not a SchoolStaffUser.');
        }

        return [
            'schoolStaffUser' => $schoolStaffUser,
        ];
    }

    /**
     * @Route("/profile/edit", name="eb_user_school_staff_profile_edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        /** @var SchoolStaffUser $schoolStaffUser */
        $schoolStaffUser = $this->getUser();
        if (!($schoolStaffUser instanceof SchoolStaffUser)) {
            return $this->createAccessDeniedException('The logged user is not a SchoolStaffUser.');
        }

        $form = $this->createForm(SchoolStaffProfileType::class, $schoolStaffUser);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($schoolStaffUser);
                $em->flush();

                return $this->redirectToRoute('eb_user_school_staff_profile_edit');
            }
        }

        return [
            'schoolStaffUser' => $schoolStaffUser,
            'form' => $form->createView(),
        ];
    }
}
