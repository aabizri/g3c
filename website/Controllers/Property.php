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

    public static function getPropertyUsers(array $get, array $post){

        $session["property_id"]=1;
        $property_id = $session["property_id"];

        $property_users = \Entities\Property::retrieve($property_id);

    }
}