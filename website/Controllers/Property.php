<?php
/**
 * Created by PhpStorm.
 * User: arnoldrandy
 * Date: 18/12/2017
 * Time: 11:26
 */

namespace Controllers;

use Repositories;
use Entities;

class Property
{


    public static function getSelectProperty(\Entities\Request $req): void {
        $user_id = $req->getUserID();
        if ($user_id === null) {
            http_response_code(403);
            echo "Non connecté";
        }
        $roles_ids = \Repositories\Roles::findAllByUserID($user_id);

    }

    public static function postNew(\Entities\Request $req): void
    {

        // Check if the data exists
        $address = $req->getPost("addresss");
        $name = $req->getPost("name");


        if ($address !== null OR $name !== null) {
            echo "erreur";
        }

        $p = new Entities\Property();
        $p->setAddress($address);
        $p->setName($name);

        try {
            Repositories\Properties::insert($p);
        } catch (\Exception $e) {
            Error::getInternalError500($e);
        }

        return;
    }

}


    /* Assign Values
    $name = $post["Name"];
    $address = $post["address"];
    $creation_date = $post["creation_date"];
    $last_updated = $post["last_updated"];

    $u = new Entities\Property();
    $u->setName($name);
    $u->setAddress($address);
    $u->setCreationDate($creation_date);
    $u->setLastUpdated($last_updated);

    $addressDuplicate = Repositories\Property::findByAddress($nick) != null;
    if ($addressDuplicate) {
        echo "A property with this address already exists";
        return;
    }

*/
