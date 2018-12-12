<?php

namespace TEHAND\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller {

    public function indexAction() {
        $content = $this->get('templating')
                ->render('TEHANDPlatformBundle:Advert:index.html.twig', array('id' => 5, 'nom' => 'Andrew'));
//        return new Response("Notre propre Hello World !");
        return new Response($content);
    }

    public function viewAction($id) {

        return $this->render('TEHANDPlatformBundle:Advert:view.html.twig', array(
                    'id' => $id
        ));
    }

    public function addAction(Request $request) {
        /*
         * gestion de variable flash en session
         */
        $session = $request->getSession();

        $session->getFlashBag()->add('info', 'Annonce bien enregistrée');
        $session->getFlashBag()->add('info', 'Oui oui cette annonce à été bien enregistrée');

        return $this->redirectToRoute('tehan_platform_view', array(
                    'id' => 8
        ));
    }

    public function viewSlugAction($slug, $year, $_format) {
        return new Response(
                "Je suis capable d'afficher l'annonce correspondant au slug" . $slug
                . "crée en " . $year . " et au format " . $_format . ".");
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
//    public function viewAction($id) {
//        
////        $response = new Response(json_encode(array('id' =>$id)));
////        $response->headers->set('Content-Type','application/json');
////        
//        //Pareil que ceci
//        return new JsonResponse(array('id' =>$id));
//    }
    // Gestion des sessions
//    public function viewAction($id, Request $request) {
//       $session = $request->getSession();
//       
//       $userId =$session->get('user_id');
//       $session->set('user_id',91);
//       return new Response("<body>Je suis une page de test, je n'ai rien à dire</body>");
//    }
}
