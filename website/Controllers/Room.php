<?php

namespace Controllers;

use Queries;
use Entities;

/**
 * Class Rooms
 * @package Controllers
 */

class Room
{

    /**
     * Crée une nouvelle pièce : POST /properties/{property_id}/rooms/create
     * @param Entities\Request $req
     */
    public function postCreate(\Entities\Request $req): void
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
            (new \Queries\Rooms)->insert($r);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }

        \Helpers\DisplayManager::redirectToController("Rooms", "Rooms");
    }

    /**
     * Récupère la liste des pièces : GET /properties/{property_id}/rooms
     * @param Entities\Room $req
     * @throws \Exception
     * @return array of rooms
     */
    public function getRooms(\Entities\Room $req): void
    {
        // Si la requête n'est pas associée à une propriété, retourner une erreur
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            http_response_code(403);
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        //Récupérer liste des pièces
        $rooms = (new \Queries\Rooms)
            ->filterByPropertyID("=", $property_id)
            ->find();

        \Helpers\DisplayManager::display("mespieces", ["rooms" => $rooms]);
    }
}