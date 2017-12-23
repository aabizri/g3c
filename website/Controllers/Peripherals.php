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
        $property_id = 1;
        if (!empty($get["pid"])) {
            $property_id = $get["pid"];
        }

        /*Vérifier que les données existent*/
        $required = ["uuid","display_name", "room_id"];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                echo "Il manque : le ".$key;
                return;
            }
        }

        /*Assigne les valeurs*/
        $uuid = $post["uuid"];
        $display_name = $post["display_name"];
        $room_id = $post["room_id"];


        /*Récupére l'entité périphérique ayant cet uuid*/
        $p = Repositories\Peripherals::retrieve($uuid);
        if ($p == null){
            return;
        }

        // Assigne les données à l'entité si le périphérique n'a pas encore de propriété.
        if(!empty($p->getPropertyID())) {
            echo "UUID déja lié à une propriété";
            return;
        }

        $p->setDisplayName($display_name);
        $p->attachToProperty($property_id);
        $p->attachToRoom($room_id);

        /*Assigner les valeurs*/
        try {
            $p = Repositories\Peripherals::push($p);
        } catch (\Throwable $t) {
            return;
        }
    }

    //Afficher les périphériques
    public function getPeripheralsPage( array $get, array $post):void
    {
        // TEMP DEBUG
        $get["property_id"] = 1;

        // Propriété transmise dans le GET -- Bizri
        $property_id = $get["property_id"];

        /*Récupère les id des capteurs sous forme de array*/
        $peripheriques_id_list = \Repositories\Peripherals::findAllByPropertyID($property_id);
        if ($peripheriques_id_list == null) {
            echo "Il n'y a pas de périphériques.";
            return;
        }

        //Récupérer les données des périphériques pour chaque périphérique
        $peripherals_list = [];
        foreach ($peripheriques_id_list as $peripherals_ID) {
            $peripherals = \Repositories\Peripherals::retrieve($peripherals_ID);
            $peripherals_list[] = $peripherals;
        }

        // Peupler la vue
        $data["peripherals_list"] = $peripherals_list;

        //Afficher
        \Helpers\DisplayManager::display("mesperipheriques",$data);
    }

    //Supprimer un périphérique
    public function postDissociatePeripheralFromProperty(array $get, array $post): void
    {
        //POUR TESTER
        $get["property_id"] = 1;


        // Propriété transmise dans le GET -- Bizri
        $property_id = $get["property_id"];

        // Périphérique à supprimer
        $uuid = $post["peripheral_id"];

        // Récupérer le périphérique
        $p = \Repositories\Peripherals::retrieve($uuid);

        // Vérifier que le périphérique est associé à la propriété
        if ($p->getPropertyID() !== $property_id) {
            echo "WTF HACKMAN";
            return;
        }

        // Supprimer le périphérique
        $p->setDisplayName(null);
        $p->setAddDate(null);
        //$p->setLastUpdated(null);
        $p->setPropertyID(null);
        $p->setRoomID(null);

        // Push
        \Repositories\Peripherals::push($p);
    }
}