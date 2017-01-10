<?php

namespace EB\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/admin-test")
     */
    public function indexAction()
    {
        return $this->render('EBAdminBundle:Default:index.html.twig');
    }
}