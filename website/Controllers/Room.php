<?php

namespace Controllers;

use Repositories;
use Entities;

/**
 * Class Rooms
 * @package Controllers
 */

class Room
{
    /*Ajouter une pièce*/
    public function postNewRoom(array $get, array $post): void
    {
        /*Vérifier que les données existent*/
        if (empty($post("name"))) {
            echo "Il manque : " . "name";
            return;
        }

        /*Assigne les valeurs*/
        $name = $post["name"];

        /*Créer l'entité*/
        $r = new Entities\Room();
        $r->setName($name);

        /*Insérer l'entité dans la bdd*/
        try {
            Repositories\Rooms::insert($r);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }

        \Helpers\DisplayManager::display("dashboard", array());
    }


    public function postDeleteRoom(array $get, array $post): void
    {
        /*Vérifier que les données existent*/
        if (empty($post("name"))) {
            echo "Il manque : " . "name";
            return;
        }

        /*Assigne les valeurs*/
        $name = $post["name"];

        /*Créer l'entité*/
        $r = new Entities\Room();
        $r->setName($name);

        /*Insérer l'entité dans la bdd*/
        try {
            Repositories\Rooms::insert($r);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }

        \Helpers\DisplayManager::display("dashboard", array());
    }


    public function getRooms(array $get, array $post): void
    {

        /*Verifier que la propriété existe */
        if(empty($_GET['pid']))
        {
            return;
        }

        //Récupérer liste des pièces
        $rooms = [];
        $room_ids= \Repositories\Rooms::findAllByPropertyID($_GET['pid']);
        foreach ($room_ids as $rid)
        {
            $room = \Repositories\Rooms::retrieve($rid);
            $rooms[] = $room;
        }

        \Helpers\DisplayManager::display("mespieces",[$rooms]);
    }

}
?>