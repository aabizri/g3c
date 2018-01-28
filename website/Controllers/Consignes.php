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
            \Controllers\Error::getBadRequest400($req, "Pas d'ID de propriété donné");
            return;
        }

        //On affiche toutes les salles diponibles de la propriété
        try {
            $property_rooms = (new \Queries\Rooms)->filterByPropertyID("=", $property_id)->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "erreur lors de la récupération des pièces");
        }

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
    public static function getRoomConsignes(\Entities\Request $req): void
    {
        //On recupère l'id de la propriété
        $property_id = $req->getPropertyID();
        if ($property_id === null) {
            \Controllers\Error::getBadRequest400($req, "Pas d'ID de propriété donné");
            return;
        }

        //On recupère l'id de salle à laquelle l'utilisateur veut accéder
        $room_id = $req->getGET("room_id");
        if (empty($room_id)) {
            \Controllers\Error::getBadRequest400($req, "Pas de RoomID donné");
            return;
        }

        // On récupère l'entité
        $room = (new Queries\Rooms)->retrieve($room_id);
        if ($room === null) {
            \Controllers\Error::getBadRequest400($req, "Pas de pièces correspondant à cette ID");
            return;
        }

        // On vérifique cette pièce appartient à la bonne propriété
        if ($room->getPropertyID() !== $property_id) {
            \Controllers\Error::getBadRequest400($req, "La propriété associé à la pièce n'est pas la propriété indiquée");
            return;
        }

        // On enregistre cette salle
        $data["room"] = $room;

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

    /**
     * POST properties/{PID}/rooms/{RID}/consignes/create
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function postCreate(\Entities\Request $req)
    {
        //On recupère l'ID de la propriété
        $property_id = $req->getPropertyID();

        // On vérifie si la propriété est bien présente
        if ($property_id === null) {
            \Controllers\Error::getBadRequest400($req, "Pas d'ID de propriété donné");
            return;
        }

        // On récupères les données en POST
        $destination_value = $req->getPOST("destination_value");
        $actuator_id = $req->getPOST("actuator_id");

        // On vérifie leur présence
        if ($actuator_id === null || $destination_value === null) {
            Error::getBadRequest400($req, "Une des valeurs (last_destination_value, actuator_id ou destination_value est null");
            return;
        }

        // On vérifie qu'elles sont bien formées
        if (!is_numeric($destination_value)) {
            Error::getBadRequest400($req, "POST destination_value n'est pas numérique: échec");
            return;
        }

        // On récupère l'actionneur afin de vérifier qu'il appartient bien à la propriété
        $actuator = (new \Queries\Actuators)->retrieve($actuator_id);
        $actuator_peripheral_uuid = $actuator->getPeripheralUUID();
        $peripheral = (new \Queries\Peripherals)->filterByColumn("uuid", "=", $actuator_peripheral_uuid)->findOne();
        if ($peripheral->getPropertyID() !== $property_id) {
            Error::getForbidden403($req, "L'actionneur indiqué n'appartient pas à cette propriété, cette action n'est pas authorisée");
            return;
        }

        // Enregistre & active la consigne
        $c = new \Entities\Consigne();
        $c->setDestinationValue($destination_value);
        $c->setActuatorID($actuator_id);
        $c->setActive(true);

        // Enregistrement
        (new \Queries\Consignes)->insert($c);

        DisplayManager::redirectToPath("consignes");
    }
}