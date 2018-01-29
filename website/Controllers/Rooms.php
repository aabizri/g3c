<?php

namespace Controllers;

use Entities;

/**
 * Class Rooms
 * @package Controllers
 */
class Rooms
{

    /**
     * Crée une nouvelle pièce : POST /properties/{property_id}/rooms/create
     * @param Entities\Request $req
     */
    public function postCreate(\Entities\Request $req): void
    {
        // Récupére l'ID de la propriété liée
        $property_id = $req->getPropertyID();

        // Si celle-ci n'est pas set, erreur
        if (empty($property_id)) {
            Error::getBadRequest400($req, "Requête non-associée à une propriété");
            return;
        }

        // Assigne & vérifie que les données existent
        $name = $req->getPOST("name");
        if (empty($name)) {
            Error::getBadRequest400($req, "Nom absent dans la requête POST");
            return;
        }

        // Créer l'entité
        try {
            $r = new Entities\Room();
            $r->setName($name);
            $r->setPropertyID($property_id);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la création d'une nouvelle pièce (pré-enregistrement)");
            return;
        }

        // Insérer l'entité dans la bdd
        try {
            (new \Queries\Rooms)->insert($r);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur dans la query insertion de la nouvelle pièce dans la BDD");
            return;
        }

        // Crée ! See other
        try {
            http_response_code(303); // HTTP Created (See other)
            header("Location: " . \Helpers\DisplayManager::absolutifyURL("properties/" . $property_id . "/rooms" ));
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de la redirection");
        }
    }

    /**
     * Récupère la liste des pièces : GET /properties/{property_id}/rooms
     * @param Entities\Request $req
     * @throws \Exception
     */
    public function getRooms(\Entities\Request $req): void
    {
        // Récupére l'ID de la propriété liée
        $property_id = $req->getPropertyID();

        // Si celle-ci n'est pas set, erreur
        if (empty($property_id)) {
            Error::getBadRequest400($req, "Requête non-associée à une propriété");
            return;
        }

        //Récupérer liste des pièces
        try {
            $rooms = (new \Queries\Rooms)
                ->filterByPropertyID("=", $property_id)
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de la query de recherche des pièces");
            return;
        }

        // Données pour la vue PHP
        $data_for_php_view = [
            "rooms" => $rooms,
            "pid" => $property_id,
        ];

        \Helpers\DisplayManager::display("mespieces", $data_for_php_view);
    }

    /**
     * Récupère la liste des dernières mesures des capteurs d'une pièce
     * @param Entities\Request $req
     * @throws \Exception
     */
    public function getRoom(\Entities\Request $req): void
    {
        // Récupére l'ID de la propriété liée
        $property_id = $req->getPropertyID();

        // Si celle-ci n'est pas set, erreur
        if (empty($property_id)) {
            Error::getBadRequest400($req, "Requête non-associée à une propriété");
            return;
        }

        //On récupère l'id de la pièce
        $rid = $req->getGET("rid");

        // Si la requête n'est pas associée à une pièce, retourner une erreur
        if (empty($rid)) {
            Error::getBadRequest400($req, "Paramètre d'ID de pièce absent");
            return;
        }

        // On la récupère
        $room = null;
        try {
            $room = (new \Queries\Rooms)->retrieve($rid);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de la récupéation de la pièce");
            return;
        }

        // On vérifie si elle existe
        if ($room === null) {
            Error::getBadRequest400($req, "La pièce indiquée n'existe pas");
            return;
        }

        // si elle n'appartient pas à la même propriété que cella actuellement selectionnée, erreur
        if ($room->getPropertyID() !== $req->getPropertyID()) {
            Error::getForbidden403($req, "Cette pièce n'est pas associée à la même propriété que celle actuelle");
            return;
        }

        // Les donnes qu'on va récupérer
        $room_sensors = [];
        $last_measures = [];

        //On récupère tout les périphériques d'une pièce.
        $peripherals = null;
        try {
            $peripherals = (new \Queries\Peripherals)
                ->filterByRoomID('=', $rid)
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de la récupération des périphériques");
            return;
        }

        /**
         *   Pour chaque péripérique on récupère son UUID,
         *   qui permet de lister les capteurs liés et les ajouter à la liste des capteur de la pièce
         * */
        foreach ($peripherals as $peripheral) {
            // Récupère la liste des capteurs associés au péiphérique
            $room_sensors_for_peripheral = null;
            try {
                $room_sensors_for_peripheral = (new \Queries\Sensors)
                    ->filterbyPeripheral('=', $peripheral)
                    ->find();
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t,
                    "Erreur lors de la récupération des capteurs du périphérique");
                return;
            }

            // Si cette liste n'est pas vide, push l'array
            if (count($room_sensors_for_peripheral) > 0) {
                array_push($room_sensors, ...$room_sensors_for_peripheral);
            }
        }


        /**
         * Pour chacun des capteurs on récupère la dernière mesure sous forme d'entité
         */
        foreach ($room_sensors as $sensor) {
            $last_measure_for_sensor = null;
            try {
                $last_measure_for_sensor = (new \Queries\Measures)
                    ->filterLastMeasureBySensor('=', $sensor)
                    ->findOne();
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t,
                    "Erreur lors de la récupération de la dernière mesure du capteur");
                return;
            }

            // Si elle n'existe pas, on s'en fout
            $last_measures[$sensor->getID()] = $last_measure_for_sensor;
        }

        $data["last_measures"] = $last_measures;
        $data["rooms"] = (new \Queries\Rooms)->filterByPropertyID("=", $property_id)->find();
        $data["room_entity"] = $room;
        $data["pid"] = $property_id;

        \Helpers\DisplayManager::display("mapiece", $data);

    }


}