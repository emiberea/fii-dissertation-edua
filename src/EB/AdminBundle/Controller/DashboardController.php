<?php

namespace EB\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="eb_admin_dashboard_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $totalAdmins = $em->getRepository('EBUserBundle:AdminUser')->countAll();
        $totalStudents = $em->getRepository('EBUserBundle:StudentUser')->countAll();
        $totalSchoolStaffUsers = $em->getRepository('EBUserBundle:SchoolStaffUser')->countAll();
        $totalSchools = $em->getRepository('EBCoreBundle:School')->countAll();
        $totalAdmissions = $em->getRepository('EBCoreBundle:Admission')->countAll();

        return $this->render('EBAdminBundle:Dashboard:index.html.twig', [
            'totalAdmins' => $totalAdmins,
            'totalStudents' => $totalStudents,
            'totalSchoolStaffUsers' => $totalSchoolStaffUsers,
            'totalSchools' => $totalSchools,
            'totalAdmissions' => $totalAdmissions,
        ]);
    }
}
