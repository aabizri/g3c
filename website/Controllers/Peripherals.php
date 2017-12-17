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
                echo "Il manque : le ".$key;
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

        // Assigne les données à l'entité si le périphérique n'a pas encore de salle.
        if( \Entities\Peripheral::getRoomID($room_id) == null) {
            $p->setDisplayName($displayname);
            $p->setRoomId($room_id);
            //$p->setPropertyId($property_id);
        }
        else {
            return;
        }

        /*Assigner les valeurs*/
        try {
            $p = Repositories\Peripherals::push($p);
        } catch (\Throwable $t) {
            return;
        }
    }

    //Afficher les périphériques

    public function afficherPeripheriques ( array $get, array $post):void
    {
        //Récupérer les infos de la session
        $r=$_SESSION["user_id"];

        //Récupérer le property_ID
        $property_id = \Entities\Peripheral::getPropertyId($r);

        /*Récupère les id des capteurs sous forme de array*/
        $peripheriques_id_list = \Repositories\Peripherals::findAllByPropertyID($property_id);
        if ($peripheriques_id_list == null) {
            echo "Il n'y a pas de périphériques.";
            return;
        }

        //Récupérer les noms des périphériques pour chaque capteur
        $peripherals_list = [];
        foreach ($peripheriques_id_list as $peripherals_ID) {
            $peripherals = \Repositories\Peripherals::retrieve($peripherals_ID);
            $peripherals_list[] = $peripherals;
        }

        foreach ($peripherals_list as $display_name){
            
        }

        foreach ($peripherals_list as $room_id){

        }

        foreach ($peripherals_list as $last_updated){

        }
    }

    //Supprimer un périphérique

    public function supprimerPeripherique (array $get, array $post): void
    {
        //Récupérer les infos de la session
        $r=$_SESSION["user_id"];

        //Récupérer le property_ID
        $property_id = \Entities\Peripheral::getPropertyId($r);

        /*Récupère les id des capteurs sous forme de array correspondant à la propriété*/
        $peripheriques_id_list = \Repositories\Peripherals::findAllByPropertyID($property_id);
        if ($peripheriques_id_list == null) {
            echo "Il n'y a pas de périphériques.";
            return;
        }

        //Récupérer les noms des périphériques pour chaque capteur
        $peripherals_list = [];
        foreach ($peripheriques_id_list as $peripherals_ID) {
            $peripherals = \Repositories\Peripherals::retrieve($peripherals_ID);
            $peripherals_list[] = $peripherals;
        }

    }
}