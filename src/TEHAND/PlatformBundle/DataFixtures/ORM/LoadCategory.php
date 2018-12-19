<?php

namespace TEHAND\PlatformBundle\DataFixures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TEHAND\PlatformBundle\Entity\Category;

class LoadCategory implements FixtureInterface{
    public function load(ObjectManager $manager){
        
        // Liste des noms de categorie à ajouter
        $names =array(
            'Développement Angular',
            
        );
        
        foreach ($names as $name){
            // On crée la catégorie
            $category = new Category();
            $category->setName($name);
            
            // On la persiste
            $manager->persist($category);
        }
        
        //On declenche l'enregistrement de toutes les catégories
        $manager->flush();
    }
}

