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
     * @param array $get
     * @param array $post
     */
    public function postCreate(array $get, array $post ): void
    {
        // Check if the data exists
        $required = ["name", "address"];
        foreach ($required as $key) {
            if (empty($post[$key])){
                echo "Missing key: ".$key;
                return;
            }
        }

        // Assign values
        $name = $post["name"];
        $address = $post["address"];


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

    public function configureProperty():void
    {
    
    }

}


