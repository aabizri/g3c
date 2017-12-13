<?php

namespace Controllers;

use Repositories;
use Entities;

/**
 * Class Peripherals
 * @package Controllers
 */

class Peripherals
{
    /*Ajouter un peripherique*/
    public function nouveaucapteur(array $get, array $post): void
    {

        /*Vérifier que les données existent*/
        $required = ["displayname", "type", "room_id"];
        foreach ($required as $key) {
            if (empty($post($key))) {
                echo "Il manque : ".$key;
                return;
            }
        }

        /*Assigne les valeurs*/
        $displayname = $post["displayname"];
        $type = $post["type"];
        $room_id = $post["room_id"];

        /*Vérifier qu'il n'existe pas d'entité avec un capteur qui a le meme nom dans la salle*/
        $nameDuplicate = Repositories\Peripherals::findAllByDisplayName($displayname) != null;
        if ($nameDuplicate) {
            echo "Un capteur a le même nom";
            return;
        }

        /*Vérifier qu'il n'existe pas d'entité avec un capteur du meme type dans la salle*/
        $sens_typeDuplicate = Repositories\Peripherals::findAllByType($type) != null;
        if ($sens_typeDuplicate) {
            echo "Un capteur identique est déja présent dans la salle";
            return;
        }

        /*Créer l'entité*/
        $u = new Entities\Peripheral();
        $u->setName($displayname);
        $u->setSenseType($type);
        $u->setRoomAssigned($room_id);

        /*Insérer l'entité dans la bdd*/
        try {
            Repositories\Peripherals::insert($u);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }
    }
}