<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 26/01/2018
 * Time: 23:35
 */

namespace Controllers;


use Helpers\DisplayManager;

class Sensorstats
{
    public static function postSensorStats( \Entities\Request $req){

        //Affichage du choix du sensor
            //Afficher les capteurs de la propriété
            $property_id = $req -> getPropertyID();

            //Liste des sensors
            $peripherals = (new \Queries\Peripherals)
                -> filterByPropertyID("=", $property_id)
                -> find();

            $peripherals_uuid = [];
            foreach ($peripherals as $p) {
                $peripherals_uuid[] = $p -> getUUID();
            }

            $sensors_list = [];
            foreach ($peripherals_uuid as $puuid){
                $sensors = (new \Queries\Sensors)
                    -> filterByColumn("peripheral_uuid", "=", $puuid, "AND")
                    -> find();

                foreach ($sensors as $s){
                    $sensors_list[] = $s;
                }
            }

            $data["sensors"] = $sensors_list;
            $data["peripherals"] = $peripherals;


        //Affichage de l'historique
        $sensor_id = $req ->getPOST("sensor_id");

        //Afficher la salle concernée pour le titre du graphique
        $sensor_entity = (new \Queries\Sensors)
            -> retrieve($sensor_id);
        $peripheral_uuid = $sensor_entity -> getPeripheralUUID();
        $peripheral_entity = (new \Queries\Peripherals())
            -> retrieve($peripheral_uuid);
        $room = (new \Queries\Rooms)
            -> retrieve($peripheral_entity -> getRoomID());

        //On récupère le nom du type de mesure que l'on va afficher
        $sensor = (new \Queries\Sensors)
            -> retrieve($sensor_id);
        $measure_type_id = $sensor -> getMeasureTypeID();
        $measure_type = (new \Queries\MeasureTypes)
            ->retrieve($measure_type_id);
        $measure_name = $measure_type -> getName();


        //On récupère l'historique du capteur classé de la plus vieille valeur à la plus récente
        $measures = (new \Queries\Measures)
                -> filterByColumn("sensor_id", "=", $sensor_id, "AND")
                -> orderBy("date_time", true)
                -> find();

        //On prend chaque valeur et on la place dans un array avec la date qui lui correspond
        $measures_values = [];
        $measures_date = [];
        foreach ($measures as $m) {
            $measures_values[] = $m -> getValue();
            $measures_date[] = $m -> getDateTime();
        }

        //On encode toutes les données pour qu'elles soient utilisable en JavaScript
        $data["roomname"] = json_encode($room -> getName()) ;
        $data["name"] = json_encode($measure_name);
        $data["values"] = json_encode($measures_values);
        $data["dates"] = json_encode($measures_date);

        //Envoie vers la vue
        DisplayManager::display("sensorstats", $data);
    }

    public static function getSelectSensor(\Entities\Request $req) {

        //Afficher les capteurs de la propriété
        $property_id = $req -> getPropertyID();

        //Liste des sensors
        $peripherals = (new \Queries\Peripherals)
            -> filterByPropertyID("=", $property_id)
            -> find();

        //On récupère l'uuid
        $peripherals_uuid = [];
        foreach ($peripherals as $p) {
            $peripherals_uuid[] = $p -> getUUID();
        }

        //On cherche tous les capteurs liés à cet uuid
        $sensors_list = [];
        foreach ($peripherals_uuid as $puuid){
            $sensors = (new \Queries\Sensors)
                -> filterByColumn("peripheral_uuid", "=", $puuid, "AND")
                -> find();

            foreach ($sensors as $s){
                $sensors_list[] = $s;
            }
        }

        //Préparation des données
        $data["sensors"] = $sensors_list;
        $data["peripherals"] = $peripherals;

        //Envoie vers la vue
        DisplayManager::display("selectsensorstats", $data);
    }
}