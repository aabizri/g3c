<?php

namespace Controllers;

use Repositories;
use Entities;

/**
 * Class Sensor
 * @package Controllers
 */

    /*Afficher les données*/
    public function afficherinfos(array $get, array $post): void
    {

        /*Vérifier que le capteur existe*/
        $required = ["id","displayname","room_id","type","last_measure","last_update"];
        foreach ( $required as $key){
            if (empty($post($key))){
                echo "Pas de données";
                return;
            }
        }

        /*Récupérer les données*/
        $room_id -> $_POST["room"];

        /*Vérfier que la salle existe*/
        $id = Repositories\Sensors::findByRoom($room_id);
        if ($id == 1) {
            $id = Repositories\Sensors::findByRoom($room_id);
        }
        if ($id == -1) {
            echo "Cette salle n'existe pas";
            return;
        }

        /*Récupérer les valeurs de l'entité*/
        $u = Repositories\Sensors::retrieve($id);



        /*Il ne reste qu'à appeler "last_measure"*/
    }
}