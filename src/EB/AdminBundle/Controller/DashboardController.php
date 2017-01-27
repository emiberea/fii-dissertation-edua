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
        return $this->render('EBAdminBundle:Dashboard:index.html.twig');
    }
}
