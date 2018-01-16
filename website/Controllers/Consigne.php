<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/01/2018
 * Time: 15:31
 */

namespace Controllers;


use Helpers\DisplayManager;
use Entities\Peripheral;
use Queries;

class Consigne
{
    public static function getConsignesPage(\Entities\Request $req) {

        $property_id = $req -> getPropertyID();

        //On affiche toutes les salles diponibles de la propriété
        $property_rooms = (new \Queries\Rooms) -> filterByPropertyID("=", $property_id) -> find();

        //On peuple la vue
        $data["property_rooms"] = $property_rooms;

        //On envoie les données vers la page
        DisplayManager::display("consignes", $data);
    }

    public static function postRoomConsignesPage(\Entities\Request $req){

        //On recupère l'id de la propriété
        $property_id = $req -> getPropertyID();

        //On recupère l'id de salle à laquelle l'utilisateur veut accéder
        $room_id = $req->getPOST("room_id");
        $data["room_name"] = (new Queries\Rooms) -> retrieve($room_id);

        //On recupère les peripherals liés à la propriété
        $property_room_peripherals = (new \Queries\Peripherals)
            -> filterByRoomID("=", $room_id)
            -> find();

        //On recupère l'UUID de chaque peripherique
        $peripherals_UUID = [];
        foreach ($property_room_peripherals as $prp) {
            $peripherals_UUID[] = $prp -> getUUID();
        }

        //Grace a l'UUID, on recupère tous les actionneurs de la salle
        $actuators = [];
        foreach ($peripherals_UUID as $pUUID) {
            $actuator = (new \Queries\Actuators)
                -> filterByPeripheralUUID("=", $pUUID)
                -> find();
            foreach ($actuator as $a) {
                $actuators[] = $a;
            }
        }

        //On envoie l'entité actionneur dans la vue
        $data["actuators"] = $actuators;

        //Rooms pour changer de salle
            //On affiche toutes les salles diponibles de la propriété
            $property_rooms = (new \Queries\Rooms) -> filterByPropertyID("=", $property_id) -> find();

            //On peuple la vue
            $data["property_rooms"] = $property_rooms;

        //On affiche
        DisplayManager::display("roomconsignes", $data);
    }

    public static function postCreateConsigne(\Entities\Request $req){

        //On recupère les données
        $post = $req -> getAllPOST();
        $destination_value = $post["destination_value"];
        $actuator_id = $post["actuator_id"];
        $last_destination_value = $post["last_destination_value"];
        if ($destination_value === $last_destination_value){
            $active = 0;
        }
        else {
            $active = 1;
        }

        $c = new \Entities\Consigne();
        $c -> setDestinationValue($destination_value);
        $c -> setActuatorID($actuator_id);
        $c -> setActive($active);

        (new \Queries\Consignes)-> save($c);

        DisplayManager::redirectToController("Consigne", "ConsignesPage&pid=1");

    }
}