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
        /**
        if (empty($property_id)) {
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        } **/

        // Assigne & vérifie que les données existent
        $name = $req->getPOST("name");
        if (empty($name)) {
            echo "Il manque le nom";
            return;
        }

        // Créer l'entité
        $r = new Entities\Room();
        $ok = $r->setName($name);
        $ok = $r->setPropertyID($property_id) ;
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

        \Helpers\DisplayManager::redirectToController("Room", "Rooms&pid=1");
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

        \Helpers\DisplayManager::display("mespieces", ["rooms" => $rooms]);
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


        if(empty($rid))
        {
            // Si la requête n'est pas associée à une pièce, retourner une erreur
            http_response_code(400);
            echo "Paramètre d'ID de pièce absent";
            return;
        }


        $room_sensors=[];
        $last_measures=[];

        //On récupère tout les périphériques d'une pièce.
        $peripherals=(new \Queries\Peripherals)
            ->filterByRoomID('=', $rid)
            ->find();


        /**
         *   Pour chaque péripérique on récupère son UUID,
         *   qui permet de lister les capteurs liés et les ajouter à la liste des capteur de la pièce
         * */
        foreach ($peripherals as $peripheral)
        {
            // Récupère la liste des capteurs associés au péiphérique
            $room_sensors_for_peripheral=(new \Queries\Sensors)
                ->filterbyPeripheral('=',$peripheral)
                ->find();

            // Si cette liste est vide, sauter au prochain
            if (count($room_sensors_for_peripheral) === 0) {
                continue;
            }

            // Sinon, push les valeurs
            array_push($room_sensors,...$room_sensors_for_peripheral);
        }



        /**
         * Pour chacun des capteurs on récupère la dernière mesure sous forme d'entité
         */
        foreach ($room_sensors as $sensor)
        {
            $last_measure_for_sensor=(new \Queries\Measures)
                ->filterLastMeasureBySensor('=',$sensor)
                ->findOne();
            $last_measures[$sensor->getID()] = $last_measure_for_sensor;



        }

        $room_entity=(new Queries\Rooms)
            ->retrieve($rid);


        $count=[];

        $data["last_measures"] = $last_measures;
        $pid = (new \Queries\Rooms)->retrieve($rid)->getPropertyID();
        $data["rooms"] = (new \Queries\Rooms)->filterByPropertyID("=",$pid)->find();
        $data["room_entity"]= $room_entity;

        \Helpers\DisplayManager::display("mapiece",$data);

    }


}