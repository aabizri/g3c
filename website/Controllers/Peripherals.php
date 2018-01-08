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
     * Ajouter un périphérique : POST /property/{property_id}/peripherals/add
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
        $peripheral = Repositories\Peripherals::retrieve($uuid);
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
            Repositories\Peripherals::push($peripheral);
        } catch (\Throwable $t) {
            return;
        }

        DisplayManager::redirectToController("Peripherals", "List");
    }

    /**
     * Récupérer la liste des périphériques : GET /property/{property_id}/peripherals
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

        // Récupère les id des capteurs sous forme de array
        $peripheriques_id_list = \Repositories\Peripherals::findAllByPropertyID($property_id);

        // Récupérer les données des périphériques pour chaque périphérique
        $peripherals_list = [];
        foreach ($peripheriques_id_list as $peripherals_ID) {
            // Récupérer les infos
            $peripheral = \Repositories\Peripherals::retrieve($peripherals_ID);

            // Ajouter les infos à la liste
            $peripherals_list[] = $peripheral;
        }

        //Trouver toutes les rooms id de la propriété
        $property_id_room = \Repositories\Rooms::findAllByPropertyID($property_id);

        //Retrieve les rooms
        $property_room=[];
        foreach ($property_id_room as $room_Id){
            $room_entity = \Repositories\Rooms::retrieve($room_Id);
            $property_room[] = $room_entity;
        }

        // Peupler la vue
        $data["peripherals_list"] = $peripherals_list;
        $data["property_room"] = $property_room;

        //Afficher
        \Helpers\DisplayManager::display("mesperipheriques",$data);

    }

    /**
     * Dis-associe un périphérique d'une entité : POST /property/{property_id}/peripherals/remove
     *
     * @param Entities\Request $req
     * @throws \Exception
     */
    public function postRemove(\Entities\Request $req): void
    {
        // Propriété transmise (ID)
        $property_id = $req->getPropertyID();

        // Périphérique à supprimer
        $uuid = $req->getPOST("peripheral_id");

        // Récupérer le périphérique
        $peripheral = \Repositories\Peripherals::retrieve($uuid);

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
        \Repositories\Peripherals::push($peripheral);

        //Affichage de la page peripherique mise a jour
        \Helpers\DisplayManager::redirectToController("Peripherals", "List");
    }
}