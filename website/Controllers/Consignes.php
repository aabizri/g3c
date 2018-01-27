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

class Consignes
{
    /**
     * GET /properties/{PID}/orders
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getConsignes(\Entities\Request $req)
    {

        $property_id = $req->getPropertyID();
        if ($property_id === null) {
            http_response_code(400);
            echo "Pas de property_id donné";;
            return;
        }

        //On affiche toutes les salles diponibles de la propriété
        $property_rooms = (new \Queries\Rooms)->filterByPropertyID("=", $property_id)->find();

        //On peuple la vue
        $data["property_rooms"] = $property_rooms;
        $data["pid"] = $property_id;

        //On envoie les données vers la page
        DisplayManager::display("consignes", $data);
    }

    /**
     * GET /properties/{PID}/rooms/orders
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getRoomConsignes(\Entities\Request $req)
    {
        //On recupère l'id de la propriété
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            http_response_code(400);
            echo "Pas de property_id donné";
            return;
        }

        //On recupère l'id de salle à laquelle l'utilisateur veut accéder
        $room_id = $req->getGET("room_id");
        if (empty($room_id)) {
            http_response_code(400);
            echo "Pas de room_id donné";
            return;
        }
        $room = (new Queries\Rooms)->retrieve($room_id);
        if ($room === null) {
            http_response_code(500);
            echo "Erreur en récupérant la pièce";
            return;
        }
        $data["room"] = $room;
        if ($room->getPropertyID() !== $property_id) {
            http_response_code(400);
            echo "La propriété associé à la pièce n'est pas la propriété indiquée";
            return;
        }

        //On recupère les peripherals liés à la propriété
        $property_room_peripherals = (new \Queries\Peripherals)
            ->filterByRoomID("=", $room_id)
            ->find();

        //Grace a l'UUID, on recupère tous les actionneurs de la salle
        $actuators = [];
        foreach ($property_room_peripherals as $peripheral) {
            $actuator = (new \Queries\Actuators)
                ->filterByPeripheralUUID("=", $peripheral->getUUID())
                ->find();
            foreach ($actuator as $a) {
                $actuators[] = $a;
            }
        }

        //On envoie l'entité actionneur dans la vue
        $data["actuators"] = $actuators;

        //Rooms pour changer de salle
        //On affiche toutes les salles diponibles de la propriété
        $property_rooms = (new \Queries\Rooms)->filterByPropertyID("=", $property_id)->find();

        //On peuple la vue
        $data["property_rooms"] = $property_rooms;
        $data["pid"] = $property_id;

        //On affiche
        DisplayManager::display("roomconsignes", $data);
    }

    public static function postCreate(\Entities\Request $req)
    {

        //On recupère les données et faisons quelques verifications
        $post = $req->getAllPOST();
        $property_id = $req->getPropertyID();

        if ($property_id === null) {
            http_response_code(400);
            echo "Pas de property_id donné";
            return;
        }

        $destination_value = $post["destination_value"];
        $actuator_id = $post["actuator_id"];
        $last_destination_value = $post["last_destination_value"];
        if ($last_destination_value === null OR $actuator_id === null OR $destination_value === null) {
            //Faire une page pour afficher l'erreur
            http_response_code(400);
            echo "Une des valeurs (last_destination_value, actuator_id ou destination_value est null";
            return;
        }
        if ($destination_value === $last_destination_value) {
            $active = 0;
        } else {
            $active = 1;
        }

        //On vérifie que l'actionneur appartient bien à la propriété
        $actuator = (new \Queries\Actuators)->retrieve($actuator_id);
        $peripheral_uuid = $actuator->getPeripheralUuid();
        $peripheral = (new \Queries\Peripherals)->filterByColumn("uuid", "=", $peripheral_uuid)->findOne();
        if ($peripheral->getPropertyID() !== $property_id) {
            Error::getInternalError500();
            return;
        }

        $c = new \Entities\Consigne();
        $c->setDestinationValue($destination_value);
        $c->setActuatorID($actuator_id);
        $c->setActive($active);

        (new \Queries\Consignes)->save($c);

        DisplayManager::redirectToPath("consignes");

    }
}