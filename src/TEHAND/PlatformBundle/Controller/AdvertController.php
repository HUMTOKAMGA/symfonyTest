<?php

namespace TEHAND\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller {

    public function indexAction() {
        $content = $this->get('templating')
                        ->render('TEHANDPlatformBundle:Advert:index.html.twig', array('nom' => 'Andrew'));
//        return new Response("Notre propre Hello World !");
        return new Response($content);
    }

}
