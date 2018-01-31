<?php

namespace Controllers;

use Helpers\DisplayManager;
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
            Error::getForbidden403($req, "Requête concernant une propriété mais non associée à une propriété, interdit");
            return;
        }

        // Assigne les valeurs
        $uuid = $req->getPOST("uuid");
        $display_name = $req->getPOST("display_name");
        $room_id = $req->getPOST("room_id");

        // Vérifie qu'elles existent
        if (empty($uuid) || empty($display_name) || empty($room_id)) {
            Error::getBadRequest400($req, "Valeur manquante en post");
            return;
        }

        // Récupére l'entité périphérique ayant cet uuid
        $peripheral = null;
        try {
            $peripheral = (new \Queries\Peripherals)->filterByUUID("=", $uuid)->findOne();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t);
        }

        // Vérifier que le périphérique existe
        if ($peripheral == null) {
            Error::getBadRequest400($req, "Ce périphérique n'existe pas");
            return;
        }

        // Assigne les données à l'entité si le périphérique n'a pas encore de propriété.
        $peripherals_associated_property_id = $peripheral->getPropertyID();
        switch ($peripherals_associated_property_id) {
            case $property_id: // Non-null mais lié à l'actuelle propriété
                Error::getBadRequest400($req, "Périphérique déjà associé à cette propriété");
                return;
            case null: // Null dont lié à aucune propriété
                break;
            default: // Non-null et non-équivalent à cette propriété
                Error::getBadRequest400($req, "Périphérique déjà associé à une autre propriété");
                return;
        }

        // Etablir les données de liaison
        try {
            $peripheral->setDisplayName($display_name);
            $peripheral->attachToProperty($property_id);
            $peripheral->attachToRoom($room_id);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t);
            return;
        }

        // Mettre à jour la BDD
        try {
            (new \Queries\Peripherals) -> save($peripheral);
        } catch (\Throwable $t) {
            return;
        }

        // Redirection en mode temporaire (303)
        DisplayManager::redirect303("properties/" . $property_id . "/peripherals");
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
            Error::getForbidden403($req, "Requête concernant une propriété mais non associée à une propriété, interdit");
            return;
        }

        // Récupère les entités des périphériques sous forme de array
        $property_peripherals = null;
        try {
            $property_peripherals = (new \Queries\Peripherals)->filterByPropertyID("=", $property_id)->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t);
            return;
        }
        //Envoyer le status des peripheriques
        foreach ($property_peripherals as $peripheral){

            //On trouve l'UUID de tous les peripheriques de la propriété
            $uuid = $peripheral -> getUUID();

            //Puis on cherche les sensors liés à ces périphériques
            $sensor = null;
            try {
                $sensor = (new \Queries\Sensors)
                    ->filterByColumn("peripheral_uuid", "=", $uuid, "AND")
                    ->find();
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req,$t,"Error find sensors");
                return;
            }
            //On recupère leurs ID
            $sensors_id = [];
            foreach ($sensor as $s){
                $sensors_id[] = $s -> getID();
            }

            //Ces ids nous permettent de trouver l'entité de la dernière mesure
            $sensors_status = [];
            foreach ($sensors_id as $sid){
                $measure = null;
                try {
                    $measure = (new \Queries\Measures)->filterByColumn("sensor_id", "=", $sid, "AND")
                        ->orderBy("date_time", false)
                        ->findOne();
                } catch (\Throwable $t) {
                    Error::getInternalError500Throwables($req,$t,"Erro retrieving last measure for sensor");
                    return;
                }

                if (isset($measure)) {
                    $date_time = $measure->getDateTime();
                }
                else{
                    //Trouver une solution
                }

                $difference = time() - strtotime($date_time);

                if ( $difference > 1800 ){
                    $status = "Non-fonctionnel";
                }
                else{
                    $status = "Fonctionnel";
                }

                $sensors_status[] = $status;
            }

            if (array_search("Non-fonctionnel", $sensors_status) !== false) {
                $final_status = "Non-fonctionnel";
            }
            elseif (empty($sensors_status)){
                $final_status = "Pas de capteurs liés";
            }
            else {
                $final_status = "Fonctionnel";
            }

            $peripheral -> setStatus($final_status);

            // Insert it
            try {
                (new \Queries\Peripherals)->update($peripheral);
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req,$t,"Error updating peripheral");
            }
        }

        //Trouver toutes les rooms id de la propriété
        $property_rooms = null;
        try {
            $property_rooms = (new \Queries\Rooms())->filterByPropertyID("=", $property_id)->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t);
            return;
        }

        // Peupler la vue
        $data_for_php_view = [
            "pid" => $property_id,
            "peripherals_list" => $property_peripherals,
            "property_room" => $property_rooms,
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
        if (empty($property_id)) {
            Error::getForbidden403($req, "Requête concernant une propriété mais non associée à une propriété, interdit");
            return;
        }

        // Périphérique à supprimer
        $uuid = $req->getGET("peripheral_id");
        if (empty($uuid)) {
            Error::getBadRequest400($req, "ne peut pas supprimer un périphérique non indiqué");
            return;
        }

        // Récupérer le périphérique
        $peripheral = null;
        try {
            $peripheral = (new \Queries\Peripherals)->retrieve($uuid);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t);
            return;
        }

        // Vérifier que le périphérique existe
        if (empty($peripheral)) {
            Error::getBadRequest400($req, "Le périphérique indiqué n'existe pas");
            return;
        }

        // Vérifier que le périphérique est associé à la propriété
        if ($peripheral->getPropertyID() !== $property_id) {
            Error::getForbidden403($req, "Périphérique non-associé à la propriété selectionnée");
            return;
        }

        // Supprimer le périphérique
        try {
            $peripheral->setDisplayName(null);
            $peripheral->setAddDate(null);
            $peripheral->setPropertyID(null);
            $peripheral->setRoomID(null);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t);
            return;
        }

        // Push
        try {
            (new \Queries\Peripherals)->update($peripheral);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t);
        }

        //Affichage de la page peripherique mise a jour
        \Helpers\DisplayManager::redirect302("properties/" . $property_id . "/peripherals");
    }
}