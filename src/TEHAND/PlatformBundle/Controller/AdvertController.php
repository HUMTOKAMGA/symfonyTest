<?php

namespace TEHAND\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TEHAND\PlatformBundle\Entity\Advert;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller {

    public function indexAction($page) {

//        if ($page < 1) {
//            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
//        }
        //recupération de l'entity Manager de doctrine
        
        $nbPerPage = 3;
        $em = $this->getDoctrine()->getManager();
        //recupération du repository d'un entity manager donné
        $advertRepository = $em->getRepository('TEHANDPlatformBundle:Advert')->getAdverts($page, $nbPerPage);
        
        // Calcule du nombre total de page en bd
        $nbPages = ceil(count($advertRepository) / $nbPerPage);
        
        // Si la page n'existe pas on retourne un 404
        if($page > $nbPages){
            throw $this->createNotFoundException("la page ".$page." n'existe pas.");
        }
        
        return $this->render('TEHANDPlatformBundle:Advert:index.html.twig', array(
                    'listAdverts' => $advertRepository,
                    'nbPages'     =>$nbPages,
                    'page'       =>$page
        ));
    }

    public function menuAction($limit) {

        //recupération de l'entity Manager de doctrine
        $em = $this->getDoctrine()->getManager();
        //recupération du repository d'un entity manager donné
        $advertRepository = $em->getRepository('TEHANDPlatformBundle:Advert')->findBy(
                array(),
                array('date' => 'desc'),
                $limit,
                0
        );

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
        if (null === $adverts) {
            throw new NotFoundHttpException("L'annonce d'id '" . $id . "' n'existe pas.");
        }

        $listApplications = $em->getRepository('TEHANDPlatformBundle:Application')
                ->findBy(array('advert' => $adverts));

        $listAdvertSkills = $em->getRepository('TEHANDPlatformBundle:AdvertSkill')
                ->findBy(array('advert' => $adverts));


        return $this->render('TEHANDPlatformBundle:Advert:view.html.twig', array(
                    'adverts' => $adverts,
                    'listApplications' => $listApplications,
                    'listAdvertSkills' => $listAdvertSkills
        ));
    }

    /*
     * function use to add annonce
     */

    public function addAction(Request $request) {

        $advert1 = new Advert();

       $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert1);
       
       $formBuilder
//               $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert1)
               ->add('date',    DateType::class ) 
               ->add('title',   TextType::class)
               ->add('content',   TextareaType::class)
               ->add('author',   TextType::class)
               ->add('published',   CheckboxType::class, array('required' => false))
               ->add('save',   SubmitType::class)
               ->getForm()
               ;
       
       if($request->isMethod('POST')){
           $formBuilder->handleRequest($request);
           
           if($formBuilder->isValid()){
               $em = $this->getDoctrine()->getManager();
               $em->persist($advert1);
               $em->flush();
               
               $request->getSession()->getFlashBag()->add('notice','Annonce bien enregistrée.');
               
               return $this->redirectToRoute('tehan_platform_view', array('id' => $advert1->getId()));
           }
       }
       
       $form = $formBuilder->getForm();
       
        //Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('TEHANDPlatformBundle:Advert:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editImageAction($advertId) {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('TEHANDPlatformBundle:Advert')->find($advertId);
        //var_dump($advert);

        $advert->getImage()->setUrl('test.png');


        $em->flush();

        return new Response('OK');
    }

    /*
     * @id is the parrams of edit action
     * function use to edit annonce
     */

    public function editAction($id, Request $request) {

        $advert1 = new Advert();

        $em = $this->getDoctrine()->getManager();

        //On recupere l'annonce d'id
        $advert = $em->getRepository('TEHANDPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . "n'existe pas.");
        }

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            //redirection vers la page de visualisation de cette annonce
            return $this->redirectToRoute('tehan_platform_view', array(
                        'id' => $advert1->getId()
            ));
        }
        return $this->render('TEHANDPlatformBundle:Advert:edit.html.twig', array(
                    'advert' => $advert
        ));

//        $listCategories = $em->getRepository('TEHANDPlatformBundle:Category')->findAll();
//        // $listAdvertCategory = $em->getRepository('TEHANDPlatformBundle:Category')->findAll();
//        //On boucle sur les catégories pour les lier à l'annonce
//        foreach ($listCategories as $category) {
//            $advert->addCategory($category);
//        }
//
//        $em->flush();

    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        //On recupere l'annonce d'id
        $advert = $em->getRepository('TEHANDPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . "n'existe pas.");
        }

        //On boucle sur les catégories pour les lier à l'annonce
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        $em->flush();


        return $this->render('TEHANDPlatformBundle:Advert:delete.html.twig');
    }

    public function viewSlugAction($slug, $year, $_format) {
        return new Response(
                "Je suis capable d'afficher l'annonce correspondant au slug" . $slug
                . "crée en " . $year . " et au format " . $_format . ".");
    }
    
    
    // D2BUT 07-01-2019
//    public function addAction(Request $request) {
//
//        $advert1 = new Advert();
//
//       if ($request->isMethod('POST')) {
//            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
//
//            //redirection vers la page de visualisation de cette annonce
//            return $this->redirectToRoute('tehan_platform_view', array(
//                        'id' => $advert1->getId()
//            ));
//        }
//
//        //Si on n'est pas en POST, alors on affiche le formulaire
//        return $this->render('TEHANDPlatformBundle:Advert:add.html.twig');
//    }
    // Fin 07-01-2019
    

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
