<?php

namespace Controllers;

use Repositories;
use Entities;

/**
 * Class Property
 * @package Controllers
 */
class Property
{
    /**
     * Create a property
     * @param Entities\Request $req
     */
    public function postCreate(\Entities\Request $req): void
    {
        // Check if the data exists
        $required = ["name", "address"];
        foreach ($required as $key) {
            if (empty($req->getPOST($key))){
                echo "Missing key: ".$key;
                return;
            }
        }

        // Assign values
        $name = $req->getPOST("name");
        $address = $req->getPOST("address");

        // Create the entity
        $p = new Entities\Property();
        $p->setName($name);
        $p->setAddress($address);

        // Insert it
        try {
            (new \Queries\Properties)
                ->save($p);
        } catch (\Exception $e) {
            echo "Error inserting property" . $e;
        }
    }


    public function getDashboard(\Entities\Request $req): void
    {
        // Si la requête n'est pas associée à une propriété, retourner une erreur
        $property_id = $req->getPropertyID();
        if (empty($property_id))
        {
            http_response_code(403);
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        //Récupérer liste des pièces de la propriété
        $rooms = (new \Queries\Rooms)
            ->filterByPropertyID("=", $property_id)
            ->find();

        // Si il n'y a pas de pièce; A basculer sur la vue.
        if(empty($rooms))
        {
            // Si la requête n'est pas associée à une pièce, retourner une erreur
            http_response_code(400);
            echo "Pas de pièces";
            return;
        }


        $last_measures_for_room_by_room_id=[];
        //Pour chaque pièce recuperer les peripheriques, puis les capteurs, puis leur dernière mesures;
        foreach ($rooms as $room)
        {
            $room_sensors=[];
            $peripherals=[];
            $last_measures_for_room_by_sensor_id=[];
            $rid=$room->getID();

            //On récupère tout les périphériques d'une pièce.
            $peripherals = (new \Queries\Peripherals)
                ->filterByRoomID('=', $rid)
                ->find();

            /*
             *   Pour chaque péripérique, on récupère tous les capteurs associés
             */
            foreach ($peripherals as $peripheral) {
                // Récupère la liste des capteurs associés au péiphérique
                $room_sensors_for_peripheral = (new \Queries\Sensors)
                    ->filterbyPeripheral('=', $peripheral)
                    ->find();

                // Push les valeurs dans l'array
                if ( count($room_sensors_for_peripheral) !== 0 ) {
                    array_push($room_sensors, ...$room_sensors_for_peripheral);
                }
            }


            /**
             * Pour chacun des capteurs on récupère la dernière \Entities\Measure
             */
            foreach ($room_sensors as $sensor) {

                // Récupérer la dernière mesure du capteur
                $last_measure_for_sensor = (new \Queries\Measures)
                    ->filterLastMeasureBySensor('=', $sensor)
                    ->findOne();

                // Cette dernière mesure est la dernière mesure du capteur
                $last_measures_for_room_by_sensor_id[$sensor->getID()] = $last_measure_for_sensor;
            }

            $last_measures_for_room_by_room_id[$rid] = $last_measures_for_room_by_sensor_id;

        }


        /*
         * La liste des \Entities\Rooms
         * @var
         */
        $data["rooms"] = $rooms;

        /*
         * [ID de Room =>
         *      ID de Capteur => Dernière mesure]
         */
        $data["last_measures"]= $last_measures_for_room_by_room_id;



        \Helpers\DisplayManager::display("dashboard",$data);

    }


}


