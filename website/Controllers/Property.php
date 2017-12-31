<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 27/12/2017
 * Time: 17:19
 */

namespace Controllers;


class Property
{
    public static function getPropertyPage (\Entities\Request $req):void
    {
        \Helpers\DisplayManager::display("mapropriete", array());
    }

    //Afficher les utilisateurs d'une propriété

    public static function getPropertyUsers(\Entities\Request $req): void {

        $_SESSION["property_id"]=2;
        $property_id = $_SESSION["property_id"];

        //$property_id = $req->getPropertyID();

        $property_users_list = \Repositories\Roles::findAllByPropertyID($property_id);
        if ($property_users_list===null){
            echo "il n'y a pas d'utilisateurs";
            return;
        }

        $users_ID_list=[];
        foreach ($property_users_list as $u){
            $u->getUserID();
            $users_ID_list[] = $u;
        }

        $users_list =[];
        foreach ($users_ID_list as $uID){
            $users = \Repositories\Users::retrieve($uID);
            $users_list[] = $users;
        }

        $data["users_list"] = $users_list;

        //Afficher
        \Helpers\DisplayManager::display("mapropriete", $data);

    }
}