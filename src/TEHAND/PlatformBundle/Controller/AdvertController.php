<?php

namespace TEHAND\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TEHAND\PlatformBundle\Entity\Advert;
use TEHAND\PlatformBundle\Entity\Image;
use \TEHAND\PlatformBundle\Entity\AdvertSkill;
use TEHAND\PlatformBundle\Entity\Application;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller {

        public function indexAction($page) {
//
//        if ($page < 1) {
//            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
//        }
            
            //recupération de l'entity Manager de doctrine
            $em = $this->getDoctrine()->getManager();            
            //recupération du repository d'un entity manager donné
            $advertRepository = $em->getRepository('TEHANDPlatformBundle:Advert')->myFindAll();


        return $this->render('TEHANDPlatformBundle:Advert:index.html.twig', array(
                    'listAdverts' => $advertRepository
        ));
    }

    public function menuAction($limit) {

        //recupération de l'entity Manager de doctrine
            $em = $this->getDoctrine()->getManager();            
            //recupération du repository d'un entity manager donné
            $advertRepository = $em->getRepository('TEHANDPlatformBundle:Advert')->findAll();

        return $this->render('TEHANDPlatformBundle:Advert:menu.html.twig', array(
                    'listAdverts' => $advertRepository
        ));
    }

    public function viewAction($id) {
        $em = $this->getDoctrine()
                ->getManager();
        
        $adverts = $em->getRepository('TEHANDPlatformBundle:Advert')->find($id);
        //var_dump($adverts);
//        $listAdvertSkills = $em->getRepository('TEHANDPlatformBundle:AdvertSkill')
//                              ->findBy(array('advert' => $adverts));
//                      var_dump($listAdvertSkills);
//        
        if(null ===$adverts){
            throw new NotFoundHttpException("L'annonce d'id '".$id."' n'existe pas.");
        }
        
        $listApplications = $em->getRepository('TEHANDPlatformBundle:Application')
                              ->findBy(array('advert' => $adverts));
        
        $listAdvertSkills = $em->getRepository('TEHANDPlatformBundle:AdvertSkill')
                              ->findBy(array('advert' => $adverts));
        
        $listAdvertApp = $em->getRepository('TEHANDPlatformBundle:Application')
                            ->getApplicationsWithAdvert(1);
                    var_dump($listAdvertApp);
       
        return $this->render('TEHANDPlatformBundle:Advert:view.html.twig', array(
                    'adverts' => $adverts,
                    'listApplications' =>$listApplications,
                    'listAdvertSkills' =>$listAdvertSkills,
                    'listAdvertApp' => $listAdvertApp
        ));
    }

    /*
     * function use to add annonce
     */

    public function addAction(Request $request) {

       $advert1 = new Advert();
       
       
       $advert1->setDate(\DateTime::createFromFormat('d-m-Y', "02-12-2018"));
       $advert1->setTitle('Recherche développeur Symfony');
       $advert1->setAuthor('Andrew');
       $advert1->setContent("Pour mission courte");
       
       $image = new Image();
       
       $image->setUrl("http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg");
       $image->setAlt("Job de rêve");
       
       $advert1->setImage($image);
        
       
       $application1 = new Application();
       $application1->setAuthor('Marine');
       $application1->setContent("J'ai toutes les qualitées requises");
       
       $application2 = new Application();
       $application2->setAuthor('Pierre');
       $application2->setContent("Je suis très motivé");
       
       $application1->setAdvert($advert1);
       $application2->setAdvert($advert1);
       //récupération de l'entity manager
       $em = $this->getDoctrine()->getManager();
       
      
       
       $em->persist($application1);
       $em->persist($application2);
       
       $listSkills = $em->getRepository('TEHANDPlatformBundle:Skill')->findAll();
       
       foreach ($listSkills as $skill) {
           $advertSkill = new AdvertSkill();
           
           $advertSkill->setAdvert($advert1);
           
           $advertSkill->setSkill($skill);
           
           $advertSkill->setLevel('Expert');
           
           $em->persist($advertSkill);
       }

       //visualisation de l'annonce dont l'id est 5
      // $advert2 = $em->getRepository('TEHANDPlatformBundle:Advert')->find(5);
       
       // Je modifie l'annonce en changeant la date
      // $advert2->setDate(new \DateTime());
       
        //Persistance de l'entité
       $em->persist($advert1);
       
       //On passe au flush tout ce qui a été persisté
       $em->flush();
       
       if($request->isMethod('POST')){
           $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
           
           //redirection vers la page de visualisation de cette annonce
           return $this->redirectToRoute('tehan_platform_view',array(
               'id' => $advert1->getId()
           ));
       }
       
       //Si on n'est pas en POST, alors on affiche le formulaire
       return $this->render('TEHANDPlatformBundle:Advert:add.html.twig',array(
            'advert' => $advert1
        ));
    }
    
    public function editImageAction($advertId) {
        $em = $this->getDoctrine()->getManager();
        
        $advert = $em->getRepository('TEHANDPlatformBundle:Advert')->find($advertId);
        var_dump($advert);
        
        $advert->getImage()->setUrl('test.png');
        
        
        $em->flush();
        
        return new Response('OK');
    }

    /*
     * @id is the parrams of edit action
     * function use to edit annonce
     */

    public function editAction($id, Request $request) {

       $em = $this->getDoctrine()->getManager();
       
       //On recupere l'annonce d'id
       $advert = $em->getRepository('TEHANDPlatformBundle:Advert')->find($id);
       
       if(null ===$advert){
           throw new NotFoundHttpException("L'annonce d'id ".$id."n'existe pas.");
       }
       
       $listCategories = $em->getRepository('TEHANDPlatformBundle:Category')->findAll();
       // $listAdvertCategory = $em->getRepository('TEHANDPlatformBundle:Category')->findAll();
       
       //On boucle sur les catégories pour les lier à l'annonce
       foreach ($listCategories as $category){
           $advert->addCategory($category);
       }
       
       $em->flush();
       
       return $this->render('TEHANDPlatformBundle:Advert:edit.html.twig',array(
            'advert' =>$advert
        ));
    }

    public function deleteAction($id) {
         $em = $this->getDoctrine()->getManager();
       
       //On recupere l'annonce d'id
       $advert = $em->getRepository('TEHANDPlatformBundle:Advert')->find($id);
       
       if(null ===$advert){
           throw new NotFoundHttpException("L'annonce d'id ".$id."n'existe pas.");
       }
       
       $listCategories = $em->getRepository('TEHANDPlatformBundle:Category')->findAll();
       
       //On boucle sur les catégories pour les lier à l'annonce
       foreach ($listCategories as $category){
           $advert->removeCategory($category);
       }
       
       $em->flush();
       

        return $this->render('TEHANDPlatformBundle:Advert:delete.html.twig',array(
            'advert' =>$advert
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
    
    
    //debut 13 - 12 - 2018
//    public function indexAction($page) {
////
////        if ($page < 1) {
////            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
////        }
//
//        $listAdverts = array(
//            array(
//                'title' => 'Recherche développeur symfony',
//                'id' => 1,
//                'author' => "Alexandre",
//                'content' => "Je recherche à present un dev "
//                . "fullstack plein demandant mission immédiat",
//                'date' => new \Datetime()),
//            array(
//                'title' => 'Recherche développeur Angular',
//                'id' => 2,
//                'author' => "Paul",
//                'content' => "Je recherche à present un dev frontEnd "
//                . "plein demandant mission immédiat",
//                'date' => new \Datetime()),
//            array(
//                'title' => 'Recherche développeur java',
//                'id' => 3,
//                'author' => "Andrew",
//                'content' => "Je recherche à present un dev BackEnd"
//                . " plein demandant mission immédiat",
//                'date' => new \Datetime())
//        );
//
//
//        return $this->render('TEHANDPlatformBundle:Advert:index.html.twig', array(
//                    'listAdverts' => $listAdverts
//        ));
//    }
    
//    public function addAction(Request $request) {
//
//        if ($request->isMethod('POST')) {
//            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée');
//
//            return $this->redirectToRoute('tehan_platform_view', array(
//                        'id' => 5
//            ));
//        }
//
//        return $this->render('TEHANDPlatformBundle:Advert:add.html.twig',array(
//            
//        ));
//    }
    
    // Fin 13 - 12 - 2018
    
    
    // Debut 17-12-2018
    
//    public function addAction(Request $request) {
//
//        //recupération du service
//        $antispam = $this->container->get('tehand_platform.antispam');
//        
//        // On part du principe que $text contient le texte d'un message quelconque
//        $text = 'qkgdfjsgfjsdguywxcbjkqsbjgqsghdvbhjkqsdqsghdjhb qksdbncqgdqsvjdbhgdqsgfqytgjknxckjc cqdsfgcuqgjkdhqsjkdquytduyqsgdkquhdihjknkcw ';
//        if ($antispam->isSpam($text)){
//            throw new \Exception('Votre méssage a été détecté comme span !');
//        }
//        return $this->render('TEHANDPlatformBundle:Advert:add.html.twig',array(
//            
//        ));
//        // Ici le message n'est pas un spam
//        
//    }
    // Fin 17 - 12 - 2018
    
    //Début 18 - 12 - 2018
//    public function viewAction($id) {
//        $adverts = array(
//            array(
//                'title' => 'Recherche développeur symfony',
//                'id' => 1,
//                'author' => "Alexandre",
//                'content' => "Je recherche à present un dev "
//                . "fullstack plein demandant mission immédiat",
//                'date' => new \Datetime(),
//                'param' => $id),
//            array(
//                'title' => 'Recherche développeur Angular',
//                'id' => 2,
//                'author' => "Paul",
//                'content' => "Je recherche à present un dev frontEnd "
//                . "plein demandant mission immédiat",
//                'date' => new \Datetime(),
//                'param' => $id),
//            array(
//                'title' => 'Recherche développeur java',
//                'id' => 3,
//                'author' => "Andrew",
//                'content' => "Je recherche à present un dev BackEnd"
//                . " plein demandant mission immédiat",
//                'date' => new \Datetime(),
//                'param' => $id),
//        );
//
//        return $this->render('TEHANDPlatformBundle:Advert:view.html.twig', array(
//                    'adverts' => $adverts
//        ));
//    }
    //End 18 - 12 - 2019
    
    //Début 19 - 12 - 2019
//    public function viewAction($id) {
//        $repository = $this->getDoctrine()
//                ->getManager()
//                ->getRepository('TEHANDPlatformBundle:Advert');
//        
//        $adverts = $repository->find($id);
//        
//        if(null ===$adverts){
//            throw new NotFoundHttpException("L'annonce d'id '".$id."' n'existe pas.");
//        }
//        return $this->render('TEHANDPlatformBundle:Advert:view.html.twig', array(
//                    'adverts' => $adverts
//        ));
//    }
    
//    public function editAction($id, Request $request) {
//
//        $advert = array(
//            'title' => 'Recherche développpeur Symfony',
//            'id' => $id,
//            'author' => 'Alexandre',
//            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
//            'date' => new \Datetime()
//        );
//
//        return $this->render('TEHANDPlatformBundle:Advert:edit.html.twig',array(
//            'advert' =>$advert
//        ));
//    }
}
