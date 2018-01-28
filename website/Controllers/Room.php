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
        //Si la requête n'est pas associée à une propriété, retourner une erreur

        $property_id = $req->getPropertyID();

        if (empty($property_id)) {
            http_response_code(400);
            return;
        }


        // Assigne & vérifie que les données existent
        $name =htmlspecialchars($req->getPOST("name"));

        if (empty($name)) {
            http_response_code(400);
            return;
        }

        // Créer l'entité
        $r = new Entities\Room();
        $r->setName($name);
        $r->setPropertyID($property_id);

        // Insérer l'entité dans la bdd
        try {
            (new \Queries\Rooms)->insert($r);
        }
        catch (\Exception $e) {}

        \Helpers\DisplayManager::redirectToController("Room", "Rooms&pid=".$property_id);
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
            return;
        }

        //Récupérer liste des pièces
        $rooms = (new \Queries\Rooms)
            ->filterByPropertyID("=", $property_id)
            ->find();

        //On envoie la liste des pièces ainsi que l'id de la propriété
        $data["rooms"]=$rooms;
        $data["pid"]=$property_id;
        \Helpers\DisplayManager::display("mespieces", $data);
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
            ->getGET("room");
        $pid=(new Queries\Rooms)->retrieve($rid)->getPropertyID();


        // On verifie si il y a une room_id
        if (empty($rid)) {
            // Si la requête n'est pas associée à une pièce, retourner une erreur
            http_response_code(400);
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

        // On récupère l'entité complète de la pièce
        $room_entity = (new Queries\Rooms)
            ->retrieve($rid);

        // On envoie les données nécessaires à la vue
        $data["last_measures"] = $last_measures;
        $pid = (new \Queries\Rooms)->retrieve($rid)->getPropertyID();
        $data["rooms"] = (new \Queries\Rooms)->filterByPropertyID("=", $pid)->find();
        $data["room_entity"] = $room_entity;
        $data["pid"]=$pid;

        \Helpers\DisplayManager::display("mapiece", $data);

    }


    public function postDelete(\Entities\Request $req): void
    {
        // On récupère le room_id via le formulaire
        $rid =$req->getPOST("rid");
        $pid=(new Queries\Rooms) ->retrieve($rid)->getPropertyID();

        // On récupère les périphérique liée à la pièce
        $peripheral_list=(new Queries\Peripherals)
            ->filterByRoomID("=",$rid)
            ->find();

        // On verifie que l'id_room et l'id_property ne sont pas vide.
        if (empty($rid)) {
            http_response_code(403);
            return;
        }
        if (empty($pid)) {
            http_response_code(403);
            return;
        }

        // On set null en room_id pour désassocier chaque périphérique à la pièce
        foreach ($peripheral_list as $peripheral)
        {
            // Supprimer le périphérique

            $peripheral->setRoomID(null);

            // Push
            (new \Queries\Peripherals) -> update($peripheral);


        }

            (new Queries\Rooms)
            ->filterByColumn("id","=",$rid)
            ->delete();

        $data["rooms"] = (new \Queries\Rooms)->filterByPropertyID("=",$pid)->find();
        \Helpers\DisplayManager::redirectToController("Room","Rooms&pid=".$pid."");

    }
}