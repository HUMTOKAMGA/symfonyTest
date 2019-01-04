<?php

namespace TEHAND\PlatformBundle\DataFixures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TEHAND\PlatformBundle\Entity\Skill;

class LoadSkill implements FixtureInterface{
    public function load(ObjectManager $manager){
        
        // Liste des noms de categorie à ajouter
        $names =array(
            'php',
            'Symfony',
            'C++',
            'Java',
            'PhotoShop',
            'Blender',
            'Bloc-note',
            
        );
        
        foreach ($names as $name){
            // On crée la compétence
            $skill = new Skill();
            $skill->setName($name);
            
            // On la persiste
            $manager->persist($skill);
        }
        
        //On declenche l'enregistrement de toutes les compétences
        $manager->flush();
    }
}

