<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 10/01/2018
 * Time: 22:02
 */

namespace Controllers;


use Helpers\DisplayManager;
use MongoDB\Driver\Query;

class CGU
{
    public static function getCGU( \Entities\Request $req){

        //On récupère les cgu
        $cgu = (new \Queries\CGU) ->orderBy("id", true) -> findOne();

        $data["cgu"] = $cgu;

        DisplayManager::display("cgu", $data);
    }

    public static function getModificateCGUPage(\Entities\Request $req){

        //On récupère les cgu
        $cgu = (new \Queries\CGU) ->orderBy("id", true) -> findOne();

        $data["cgu"] = $cgu;

        DisplayManager::display("modificationcgu" , $data);
    }

    public static function postModificateCGU (\Entities\Request $req){

        //Récupérer les données
        $cgu = new \Entities\CGU();
        $cgu -> setText($req->getPOST("cgu"));

        //Insertion
        try {
            (new \Queries\CGU)->save($cgu);
        } catch (\Exception $e) {
            echo "Error: " . $e;
        }

    }
}