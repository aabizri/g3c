<?php

namespace Controllers;

use Repositories;
use Entities;

/**
 * Class Rooms
 * @package Controllers
 */

class Room
{
    /*Ajouter une pièce*/
    public function postNouvellepiece(array $get, array $post): void
    {
        /*Vérifier que les données existent*/
        $required = ["name"];
        if (empty($post("name"))) {
            echo "Il manque : "."name";
            return;
            }
        
        /*Assigne les valeurs*/
        $name = $post["name"];


        /*Créer l'entité*/
        $r = new Entities\Room();
        $r->setName($name);


        /*Insérer l'entité dans la bdd*/
        try {
            Repositories\Rooms::insert($r);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }
    }
}