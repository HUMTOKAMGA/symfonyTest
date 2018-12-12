<?php

namespace TEHAND\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller {

    public function indexAction($page) {
        
        if($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        
        return $this->render('TEHANDPlatformBundle:Advert:index.html.twig');
    }

    public function viewAction($id) {

        return $this->render('TEHANDPlatformBundle:Advert:view.html.twig', array(
                    'id' => $id
        ));
    }

    /*
     * function use to add annonce
     */
    public function addAction(Request $request) {
        
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée');
            
            return $this->redirectToRoute('tehan_platform_view', array(
                'id' => 5
            ));
        }
        
        return $this->redirectToRoute('TEHANDPlatformBundle:Advert:add.html.twig');
    }

    /*
     * @id is the parrams of edit action
     * function use to edit annonce
     */
    public function editAction($id, Request $request) {
        
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice','Annonce bien modifiée');
            
            return $this->redirectToRoute('tehan_platform_view', array(
                'id' => 5
            ));
        }
        
        return $this->redirectToRoute('TEHANDPlatformBundle:Advert:edit.html.twig');
    }
    
    public function deleteAction($id){
        
        
        return $this->render('TEHANDPlatformBundle:Advert:delete.html.twig');
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
    
    
//     public function addAction(Request $request) {
//        /*
//         * gestion de variable flash en session
//         */
//        $session = $request->getSession();
//
//        $session->getFlashBag()->add('info', 'Annonce bien enregistrée');
//        $session->getFlashBag()->add('info', 'Oui oui cette annonce à été bien enregistrée');
//
//        return $this->redirectToRoute('tehan_platform_view', array(
//                    'id' => 8
//        ));
//    }
}
