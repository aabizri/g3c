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
    public function postNewRoom(\Entities\Request $req): void
    {
        // Si la requête n'est pas associée à une propriété, retourner une erreur
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        /*Vérifier que les données existent*/
        if (empty($req->getPOST("name"))) {
            echo "Il manque le nom";
            return;
        }

        /*Assigne les valeurs*/
        $name = $req->getPOST("name");

        /*Créer l'entité*/
        $r = new Entities\Room();
        $ok = $r->setName($name);
        if ($ok === false) {
            echo "Il y a une erreur";
            return;
        }

        /*Insérer l'entité dans la bdd*/
        try {
            Repositories\Rooms::insert($r);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }

        \Helpers\DisplayManager::redirectToController("Rooms", "RoomsPage");
    }

    public function getRooms(\Entities\Request $req): void
    {
        // Si la requête n'est pas associée à une propriété, retourner une erreur
        $property_id = $req->getPropertyID();
        if (empty($property_id)) {
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        //Récupérer liste des pièces
        $rooms = [];
        $room_ids= \Repositories\Rooms::findAllByPropertyID($property_id);
        foreach ($room_ids as $rid)
        {
            $room = \Repositories\Rooms::retrieve($rid);
            $rooms[] = $room;
        }

        \Helpers\DisplayManager::display("mespieces",[$rooms]);
    }