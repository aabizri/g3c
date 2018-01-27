<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 10/01/2018
 * Time: 22:02
 */

namespace Controllers;


use Helpers\DisplayManager;
use Queries;

class CGU
{
    public static function getCGU( \Entities\Request $req){

        //On récupère les cgu
        $cgu = (new \Queries\CGU) ->orderBy("id", false) -> findOne();

        $data["cgu"] = $cgu;

        DisplayManager::display("cgu", $data);
    }

    public static function getModifyCGUPage(\Entities\Request $req){

        //On récupère les cgu
        $cgu = (new \Queries\CGU) ->orderBy("id", false) -> findOne();

        $data["cgu"] = $cgu;

        DisplayManager::display("modificationcgu" , $data);
    }

    /**
     * ADMIN

    public static function postModifyCGU (\Entities\Request $req){

        //Récupérer les données
        $cgu = new \Entities\CGU();
        $cgu -> setText($req->getPOST("cgu"));

        //Insertion
        try {
            (new \Queries\CGU)->save($cgu);
            DisplayManager::redirectToController("CGU" , "ModificateCGUPage");
        } catch (\Exception $e) {
            echo "Error: " . $e;
        }

    }

     **/

}