<?php
/**
 * Created by PhpStorm.
 * User: arnoldrandy
 * Date: 18/12/2017
 * Time: 11:26
 */

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class Property
{

}
        // Assign Values
    $name = $post["Name"];
    $address = $post["address"];
    $creation_date = $post["creation_date"];
    $last_updated = $post["last_updated"];

    $u = new Entities\Property();
    $u->setName($name);
    $u->setAddress($address);
    $u->setCreationDate($creation_date);
    $u->setLastUpdated($last_updated);
