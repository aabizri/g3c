<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/01/2018
 * Time: 15:31
 */

namespace Controllers;


use Helpers\DisplayManager;

class Filtre
{
    public static function getFiltersPage(\Entities\Request $req) {

        DisplayManager::display("mesfiltres");
    }
}