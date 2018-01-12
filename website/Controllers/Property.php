<?php

namespace Controllers;

use Repositories;
use Entities;

/**
 * Class Property
 * @package Controllers
 */
class Property
{
    /**
     * Create a property
     * @param Entities\Request $req
     */

    public static function postJoin(\Entities\Request $req): void
    {
        // Récupere le post
        $post = $req->getAllPOST();

        // Check if the data exists
        $required = ["address", "name"];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                echo "Missing key: " . $key;
                return;
            }
        }

        // Create the entit
        // Include la page de confirmation
        \Helpers\DisplayManager::redirectToController("Property", "Dashboard");
    }


    public function postCreate(\Entities\Request $req): void
    {
        // Check if the data exists
        $required = ["name", "address"];
        foreach ($required as $key) {
            if (empty($req->getPOST($key))){
                echo "Missing key: ".$key;
                return;
            }
        }

        // Assign values
        $name = $req->getPOST("name");
        $address = $req->getPOST("address");

        // Create the entity
        $p = new Entities\Property();
        $p->setName($name);
        $p->setAddress($address);

        // Insert it
        try {
            Repositories\Properties::insert($p);
        } catch (\Exception $e) {
            echo "Error inserting property" . $e;
        }
    }


    public static function getMyProperties(\Entities\Request $req): void
    {
        \Helpers\DisplayManager::display("mesproprietes");
    }

}


