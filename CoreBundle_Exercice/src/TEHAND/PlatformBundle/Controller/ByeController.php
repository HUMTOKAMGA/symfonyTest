<?php

namespace TEHAND\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ByeController extends Controller {

    public function byeAction() {
        $content = $this->get('templating')
                        ->render('TEHANDPlatformBundle:Bye:bye.html.twig', array('nom' => 'Andrew'));
//        return new Response("Notre propre Hello World !");
        return new Response($content);
    }

}
