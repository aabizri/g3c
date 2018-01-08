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

        // Assigne & vérifie que les données existent
        $name = $req->getPOST("name");
        if (empty($name)) {
            echo "Il manque le nom";
            return;
        }

        // Créer l'entité
        $r = new Entities\Room();
        $ok = $r->setName($name);
        if ($ok === false) {
            http_response_code(400);
            echo "Il y a une erreur dans le nom et/ou prénom";
            return;
        }

        // Insérer l'entité dans la bdd
        try {
            Repositories\Rooms::insert($r);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }

        \Helpers\DisplayManager::redirectToController("Rooms", "RoomsPage");
    }

    public function getRooms(\Entities\Request $req): void
    {
        // Si la requête n'est pas associée à une propriété, retourner une erreur
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            http_response_code(403);
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        //Récupérer liste des pièces
        $rooms = [];
        $room_ids = \Repositories\Rooms::findAllByPropertyID($property_id);
        foreach ($room_ids as $rid) {
            $room = \Repositories\Rooms::retrieve($rid);
            $rooms[] = $room;
        }

        \Helpers\DisplayManager::display("mespieces", [$rooms]);
    }
}