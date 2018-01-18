<?php

namespace Controllers;

use MongoDB\Driver\Query;
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
            if (empty($req->getPOST($key))) {
                echo "Missing key: " . $key;
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
            (new \Queries\Properties)
                ->save($p);
        } catch (\Exception $e) {
            echo "Error inserting property" . $e;
        }
    }


    public static function getMyProperties(\Entities\Request $req): void
    {
        $user_id = $req -> getUserID();
        $user_id = 4;

        // Récupère l'ID de l'utilisatreur
        //$u = $req->getUser();
        //if ($u === null) {
            //http_response_code(403);
            //return;
        //}
        // Récupère tous les rôles associés à l'utilisateur

        // Pour chaque rôle, tu récupère la propriété associé, et tu l'ajoute à une liste
        //$data["properties"] = $properties;

        $roles = (new \Queries\Roles) -> filterByUserID("=", $user_id) -> find();
        $properties_id = [];
        foreach ($roles as $r){
            $properties_id[] = $r -> getPropertyID();
        }

        $properties = [];
        foreach ($properties_id as $pid){
            $properties[] = (new \Queries\Properties) -> retrieve($pid);
        }
        $data["properties"] = $properties;

        \Helpers\DisplayManager::display("mesproprietes", $data);
    }

    public static function getNewProperty(\Entities\Request $req): void
    {
        \Helpers\DisplayManager::display("nouvellepropriete");
    }

    public static function postNew(\Entities\Request $req): void
    {

        // Extraire les données
        $UserID=$req -> getUserID();
        $UserID=4;
        $name = $req->getPOST("name");
        if (empty($name)) return;
        $address = $req->getPOST("address");
        if (empty($address)) return;

        // Create the entity
        $p = new Entities\Property();
        $p->setName($name);
        $p->setAddress($address);

        // Insert it
        try {
            (new \Queries\Properties)->save($p);
        } catch (\Throwable $t) {
            Error::getInternalError500($req,$t);
            return;
        }

        $property_id = $p ->getID();

        //Create role entity
        $r = new Entities\Role();
        $r->setUserID($UserID);
        $r->setPropertyID($property_id);

        // Insert it
        try {
            (new \Queries\Roles)->save($r);
        } catch (\Throwable $t) {
            Error::getInternalError500($req,$t);
            return;
        }

        // Include la page de confirmation
        \Helpers\DisplayManager::redirectToController("Property", "MyProperties");
    }




}