<?php

namespace Controllers;

use Entities;

/**
 * Class Rooms
 * @package Controllers
 */use Queries;

class Rooms
{

    /**
     * Crée une nouvelle pièce : POST /properties/{property_id}/rooms/create
     * @param Entities\Request $req
     */
    public function postCreate(\Entities\Request $req): void
    {
        //Si la requête n'est pas associée à une propriété, retourner une erreur

        $property_id = $req->getPropertyID();

        if (empty($property_id)) {
            http_response_code(400);
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        // Assigne & vérifie que les données existent
        $name = $req->getPOST("name");
        if (empty($name)) {
            http_response_code(400);
            echo "Il manque le nom";
            return;
        }

        // Créer l'entité
        $r = new Entities\Room();
        $r->setName($name);
        $r->setPropertyID($property_id);

        // Insérer l'entité dans la bdd
        try {
            (new \Queries\Rooms)->insert($r);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }

        http_response_code(303); // HTTP Created
        header("Location: " . \Helpers\DisplayManager::absolutifyURL("properties/" . $property_id . "/rooms/" . $r->getID()));
        //\Helpers\DisplayManager::redirectToPath("properties/" . $property_id . "/rooms/" . $r->getID());
    }

    /**
     * Récupère la liste des pièces : GET /properties/{property_id}/rooms
     * @param Entities\Request $req
     * @throws \Exception
     */
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
        $rooms = (new \Queries\Rooms)
            ->filterByPropertyID("=", $property_id)
            ->find();

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
        //On récupère l'id de la pièce
        $rid = $req
            ->getGET("rid");
        if (empty($rid)) {
            // Si la requête n'est pas associée à une pièce, retourner une erreur
            http_response_code(400);
            echo "Paramètre d'ID de pièce absent";
            return;
        }

        // On vérifie si elle existe / on la récupère
        $room = (new \Queries\Rooms)->retrieve($rid);
        if ($room === null) {
            http_response_code(400);
            echo "Cette pièce n'existe pas";
            return;
        }
        if ($room->getPropertyID() !== $req->getPropertyID()) {
            http_response_code(400);
            echo "Cette pièce n'est pas associée à la même propriété que celle actuelle";
            return;
        }


        $room_sensors = [];
        $last_measures = [];

        //On récupère tout les périphériques d'une pièce.
        $peripherals = (new \Queries\Peripherals)
            ->filterByRoomID('=', $rid)
            ->find();


        /**
         *   Pour chaque péripérique on récupère son UUID,
         *   qui permet de lister les capteurs liés et les ajouter à la liste des capteur de la pièce
         * */
        foreach ($peripherals as $peripheral) {
            // Récupère la liste des capteurs associés au péiphérique
            $room_sensors_for_peripheral = (new \Queries\Sensors)
                ->filterbyPeripheral('=', $peripheral)
                ->find();

            // Si cette liste est vide, sauter au prochain
            if (count($room_sensors_for_peripheral) === 0) {
                continue;
            }

            // Sinon, push les valeurs
            array_push($room_sensors, ...$room_sensors_for_peripheral);
        }


        /**
         * Pour chacun des capteurs on récupère la dernière mesure sous forme d'entité
         */
        foreach ($room_sensors as $sensor) {
            $last_measure_for_sensor = (new \Queries\Measures)
                ->filterLastMeasureBySensor('=', $sensor)
                ->findOne();
            $last_measures[$sensor->getID()] = $last_measure_for_sensor;


        }


        $data["last_measures"] = $last_measures;
        $pid = (new \Queries\Rooms)->retrieve($rid)->getPropertyID();
        $data["rooms"] = (new \Queries\Rooms)->filterByPropertyID("=", $pid)->find();
        $data["room_entity"] = $room;
        $data["pid"] = $req->getPropertyID();

        \Helpers\DisplayManager::display("mapiece", $data);

    }


}