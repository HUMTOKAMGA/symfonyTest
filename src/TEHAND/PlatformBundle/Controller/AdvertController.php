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
    
    public function viewAction($id)
    {
        return new Response("Affichage de l'annonce d'id: ".$id);
    }
    
    public function viewSlugAction($slug, $year, $_format)
    {
        return new Response(
                "Je suis capable d'afficher l'annonce correspondant au slug".$slug
                ."crée en ".$year." et au format ".$_format.".");
    }

}
