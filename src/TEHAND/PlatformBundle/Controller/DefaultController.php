<?php

namespace TEHAND\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TEHANDPlatformBundle:Default:index.html.twig');
    }
}
