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
    public function postNewRoom(\Entities\Request $req): void
    {
        // Si la requête n'est pas associée à une propriété, retourner une erreur
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        // Récupérer le post
        $post = $req->getPOST();

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

        header("Location: " . \Helpers\DisplayManager::absolutifyURL("index.php?c=User&a=RoomsPage"));
    }

    public static function getRoomsPage (\Entities\Request $req):void
    {
        // Si la requête n'est pas associée à une propriété, retourner une erreur
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }
        
        \Helpers\DisplayManager::display("mespieces");
    }
}