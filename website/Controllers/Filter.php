<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/01/2018
 * Time: 15:31
 */

namespace Controllers;


use Helpers\DisplayManager;
use Entities\Peripheral;

class Filter
{
    public static function getFiltersPage(\Entities\Request $req) {

        //On recupère l'id de la propriété
        $property_id = $req -> getPropertyID();

        //On recupère l'id de salle à laquelle l'utilisateur veut accéder TODO: Liste déroulante des salles qui renvoie un room_id
        $room_id = $req->getPOST("room_id");
        $room_id = 2;

        //On recupère les peripherals liés à la propriété
        $property_room_peripherals = (new \Queries\Peripherals)
                -> filterByRoomID("=", $room_id)
                -> filterByPropertyID("=", $property_id)
                -> find();
        var_dump($property_room_peripherals);
        return;

        //On recupère l'UUID de chaque peripherique
        $peripherals_UUID = [];
        foreach ($property_room_peripherals as $prp) {
            $peripherals_UUID = $prp -> getUUID();
        }

        //Grace a l'UUID, on recupère tous les capteurs de la salle
        $sensors = [];
        foreach ($peripherals_UUID as $pUUID) {
            (new \Queries\Sensors)
                -> filterByUUID($pUUID)
                -> find();
        }

        //Grace aux entités capteurs, on recupère l'id des types de mesures de chaque capteur
        $measures_type_id = [];
        foreach ($sensors as $s){
            $s -> getMeasureTypeID();
            $measures_type_id[]= $s;
        }

        //On recupère les id des measure
        $measures_id = [];
        foreach ($sensors as $s){
            $s -> getMeasureID();
            $measures_id[] = $s;
        }

        //Enfin on recupère l'entité measure_type et l'entité measure
        $measures_type = [];
        $measure = [];
        foreach ($measures_type_id as $mtid) {
            $measures_type[] = (new \Queries\MeasureTypes)-> retrieve($mtid);
        }
        foreach ($measures_id as $mid){
            $measure[] = (new \Queries\Measures) -> retrieve($mid);
        }

        //On prepare le peuplement de la view
        $data["measure_type"] = $measures_type;

        //On envoie les données vers la page
        DisplayManager::display("mesfiltres", $data);
    }

    public static function postCreateFilter(\Entities\Request $req){

        //On recupère les données
        $post = $req -> getAllPOST();
        $property_id = $req ->getPropertyID();
        $sensor_id = $post["sensor_id"];
        //TODO opérateur grace à une boucle if qui compare la valeur initiale et la valeur demandée
        //TODO qu'est ce qu'un opérateur ? < > = ?
        $threshold = $post["threshold"];
        $last_measure = $post["last_measure"];

    }
}