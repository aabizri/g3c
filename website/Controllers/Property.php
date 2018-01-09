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
            (new \Queries\Properties)
                ->save($p);
        } catch (\Exception $e) {
            echo "Error inserting property" . $e;
        }
    }
}


