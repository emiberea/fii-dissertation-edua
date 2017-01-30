<?php

namespace EB\UserBundle\Controller;

use EB\UserBundle\Entity\StudentUser;
use EB\UserBundle\Form\Type\StudentProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/student")
 */
class StudentProfileController extends Controller
{
    /**
     * @Route("/profile", name="eb_user_student_profile_show")
     * @Template()
     */
    public function showAction()
    {
        /** @var StudentUser $student */
        $student = $this->getUser();
        if (!($student instanceof StudentUser)) {
            throw $this->createAccessDeniedException('The logged user is not a Student.');
        }

        return [
            'student' => $student,
        ];
    }

    /**
     * @Route("/profile/edit", name="eb_user_student_profile_edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        /** @var StudentUser $student */
        $student = $this->getUser();
        if (!($student instanceof StudentUser)) {
            throw $this->createAccessDeniedException('The logged user is not a Student.');
        }

        $form = $this->createForm(StudentProfileType::class, $student);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($student);
                $em->flush();

                return $this->redirectToRoute('eb_user_student_profile_edit');
            }
        }

        return [
            'student' => $student,
            'form' => $form->createView(),
        ];
    }
}
