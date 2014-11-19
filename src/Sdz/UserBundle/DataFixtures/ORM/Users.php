<?php
// src/Sdz/UserBundle/DataFixtures/ORM/Users.php

namespace Sdz\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sdz\UserBundle\Entity\User;

class Users implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // Liste des noms de catégorie à ajouter
        $noms = array('Ben', 'winzou', 'John', 'Évènement');
        $users = array();

        foreach($noms as $i => $nom)
        {
            // On crée la catégorie
            $users[$i] = new User();
            $users[$i]->setUsername($nom);
            $users[$i]->setPassword($nom);
            $users[$i]->setSalt('');
            $users[$i]->setRoles(array());

            // On la persiste
            $manager->persist($users[$i]);
        }

        // On déclenche l'enregistrement
        $manager->flush();
    }
}