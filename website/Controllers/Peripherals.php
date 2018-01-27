<?php

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

/**
 * Class Peripherals
 * @package Controllers
 */

class Peripherals
{

    /**
     * Ajouter un périphérique : POST /properties/{property_id}/peripherals/add
     *
     * @param Entities\Request $req
     * @throws \Exception
     */
    public function postAdd(\Entities\Request $req): void
    {
        // On récupère l'id de la propriété
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            http_response_code(403);
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        // Assigne les valeurs
        $uuid = $req->getPOST("uuid");
        $display_name = $req->getPOST("display_name");
        $room_id = $req->getPOST("room_id");

        // Vérifie qu'elles existent
        if (empty($uuid) || empty($display_name) || empty($room_id)) {
            http_response_code(400); // BAD REQUEST
            echo "Valeur manquante";
            return;
        }

        // Récupére l'entité périphérique ayant cet uuid
        $peripheral = (new \Queries\Peripherals) -> filterByUUID("=", $uuid) -> findOne();
        if ($peripheral == null) {
            return;
        }

        // Assigne les données à l'entité si le périphérique n'a pas encore de propriété.
        $peripherals_associated_property_id = $peripheral->getPropertyID();
        switch ($peripherals_associated_property_id) {
            case $property_id: // Non-null mais lié à l'actuelle propriété
                http_response_code(400);
                echo "UUID déja lié à cette propriété";
                return;
            case null: // Null dont lié à aucune propriété
                break;
            default: // Non-null et non-équivalent à cette propriété
                http_response_code(400);
                echo "UUID déjà lié à un autre propriété";
                return;
        }

        // Etablir les données de liaison
        $peripheral->setDisplayName($display_name);
        $peripheral->attachToProperty($property_id);
        $peripheral->attachToRoom($room_id);

        // Mettre à jour la BDD
        try {
            (new \Queries\Peripherals) -> save($peripheral);
        } catch (\Throwable $t) {
            return;
        }

        DisplayManager::redirectToPath("properties/" . $property_id . "/peripherals");
    }

    /**
     * Récupérer la liste des périphériques : GET /properties/{property_id}/peripherals
     *
     * @param Entities\Request $req
     * @throws \Exception
     */
    public function getList(\Entities\Request $req): void
    {
        // On récupère l'id de la propriété actuelle
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            http_response_code(403);
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        // Récupère les entités des périphériques sous forme de array
        $peripherals_list = (new \Queries\Peripherals) -> filterByPropertyID("=", $property_id) -> find();


        //Trouver toutes les rooms id de la propriété
        $property_room = (new \Queries\Rooms()) -> filterByPropertyID("=", $property_id) -> find();

        // Peupler la vue
        $data_for_php_view = [
            "pid" => $property_id,
            "peripherals_list" => $peripherals_list,
            "property_room" => $property_room,
        ];

        //Afficher
        \Helpers\DisplayManager::display("mesperipheriques", $data_for_php_view);

    }

    /**
     * Dis-associe un périphérique d'une entité : POST /properties/{property_id}/peripherals/remove
     *
     * @param Entities\Request $req
     * @throws \Exception
     */
    public function postRemove(\Entities\Request $req): void
    {
        // Propriété transmise (ID)
        $property_id = $req->getPropertyID();
        if (!is_numeric($property_id)) {
            http_response_code(400);
            echo "ID non numérique :" . $property_id;
            return;
        }
        $property_id = (int)$property_id;

        // Périphérique à supprimer
        $uuid = $req->getGET("peripheral_id");
        if (empty($uuid)) {
            http_response_code(400);
            echo "ne peut pas supprimer un périphérique non indiqué";
            return;
        }

        // Récupérer le périphérique
        $peripheral = (new \Queries\Peripherals) ->filterByUUID("=", $uuid) ->findOne();

        // Vérifier que le périphérique existe
        if (empty($peripheral)) {
            http_response_code(400);
            echo "Ce périphérique n'existe pas";
            return;
        }

        // Vérifier que le périphérique est associé à la propriété
        if ($peripheral->getPropertyID() !== $property_id) {
            http_response_code(403);
            echo "Périphérique non-associé à la propriété selectionnée";
            return;
        }

        // Supprimer le périphérique
        $peripheral->setDisplayName(null);
        $peripheral->setAddDate(null);
        $peripheral->setPropertyID(null);
        $peripheral->setRoomID(null);

        // Push
        (new \Queries\Peripherals) -> update($peripheral);

        //Affichage de la page peripherique mise a jour
        \Helpers\DisplayManager::redirectToPath("properties/" . $property_id . "/peripherals");
    }
}