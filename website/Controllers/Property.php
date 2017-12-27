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
}