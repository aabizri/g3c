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
    public static function getSelect(\Entities\Request $req): void
    {
        $u = $req->getUser();
        if ($u === null) {
            http_response_code(403);
            return;
        }

        // Récupère tous les rôles associés à l'utilisateur
        // Pour chaque rôle, tu récupère la propriété associé, et tu l'ajoute à une liste
        $roles = (new \Queries\Roles)->filterByUser("=", $u)->find();
        $properties_id = [];
        foreach ($roles as $r){
            $properties_id[] = $r -> getPropertyID();
        }

        $properties_query = new \Queries\Properties;
        foreach ($properties_id as $pid){
            $properties[] = $properties_query->filterByColumn("id", "=", $pid, "OR");
        }
        $properties = $properties_query->find();
        $data["properties"] = $properties;

        \Helpers\DisplayManager::display("mesproprietes", $data);
    }

    public static function getNew(\Entities\Request $req): void
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
        \Helpers\DisplayManager::redirectToPath("index.php?c=Property&a=Select");
    }
}