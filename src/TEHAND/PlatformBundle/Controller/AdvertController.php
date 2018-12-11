<?php

namespace TEHAND\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

//use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller {

    public function indexAction() {
        $content = $this->get('templating')
                ->render('TEHANDPlatformBundle:Advert:index.html.twig', array('id' => 5, 'nom' => 'Andrew'));
//        return new Response("Notre propre Hello World !");
        return new Response($content);
    }

//    public function viewAction($id, Request $request) {
//        
//        $tag = $request->query->get('tag');        
//        return $this->render(
//                'TEHANDPlatformBundle:Advert:view.html.twig', array(
//                    'id' => $id, 
//                    'tag' => $tag
//                ));
//    }
    
    public function viewAction($id) {
        
//        $url = $this->get('router')->generate('tehan_platform_home');
//        
//        return new RedirectResponse($url);
        
        //Pareil que ceci
        return $this->redirectToRoute('tehan_platform_home');
    }

    public function viewSlugAction($slug, $year, $_format) {
        return new Response(
                "Je suis capable d'afficher l'annonce correspondant au slug" . $slug
                . "crée en " . $year . " et au format " . $_format . ".");
    }

}
