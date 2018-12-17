<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AccueilController extends Controller {
    
    public function indexAction(){
        
        return $this->render('CoreBundle:Accueil:accueil.html.twig');
    }
    
    public function contactAction(Request $request) {
        /*
         * gestion de variable flash en session
         */
        $session = $request->getSession();

        $session->getFlashBag()->add('info', 'La page de contact n’est pas encore disponible');

        return $this->redirectToRoute('core_homepage');
    }
    
}