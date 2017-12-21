<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 9:15 PM
 */

namespace Repositories;

use Entities;
use Controllers;



/**
 * Class Sensor
 * @package Controllers
 */


class Sensor{

    /*Afficher les données
    use \Repositories\Sensors */

    public function getInfo(array $get, array $post, $values): void
    {
        /*Vérifier que le capteur existe*/
        $required = ["id", "peripheral_uuid", "sense_type", "last_measure", "last_update"];
        foreach ($required as $key) {
            if (empty($post($key))) {
                echo "Pas de données";
                return;
            }
        }

        /*Récupérer les données*/
        $room_id = $_POST["room_id"];

        /*Vérfier que la salle existe*/
        \Repositories\Rooms::retrieve($room_id);
        if ($room_id == null) {
            echo "Cette salle n'existe pas";
            return;
        }

        /*Récupère les id des capteurs sous forme de array*/
        $sensor_id_list = \Repositories\Sensors::findAllByRoomID($room_id);
        if ($sensor_id_list == null) {
            echo "Il n'y a pas de capteur dans cette pièce.";
            return;
        }

        /* Pour chaque capteur, récupère la dernière valeur */
        $sensor_list = [];
        foreach ($sensor_id_list as $sensor_id) {
            $sensor = \Repositories\Sensors::retrieve($sensor_id);
            $sensor_list[] = $sensor;
        }

        foreach($sensor_list as $s)
        {
            $last_measure = $s-> getLastMeasure();

        }


        foreach ($s as $i)
        {
            echo ''.$sensor_list[$i].'->'.$s[$i].'';
        }
    }


}
