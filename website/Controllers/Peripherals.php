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
    public function postAddPeripheral(array $get, array $post): void
    {
        /*Vérifier que les données existent*/
        $required = ["uuid","displayname", "room_id"];
        foreach ($required as $key) {
            if (empty($post($key))) {
                echo "Il manque : ".$key;
                return;
            }
        }

        /*Assigne les valeurs*/
        $uuid = $post["uuid"];
        $displayname = $post["displayname"];
        $room_id = $post["room_id"];


        /*Récupére l'entité périphérique ayant cet uuid*/
        $p = Repositories\Peripherals::retrieve($uuid);
        if ($p == null){
            return;
        }

        // Assigne les donéees à l'entité
        $p->setDisplayName($displayname);
        $p->setRoomId($room_id);
        //$p->setPropertyId($property_id);

        /*Assigner la valeur room_id*/
        try {
            $p = Repositories\Peripherals::push($p);
        } catch (\Throwable $t) {
            return;
        }
    }
}