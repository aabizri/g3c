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

        $sensor_id = $req ->getPOST("sensor");

        $sensor_measures = (new \Queries\Measures)
                -> filterByColumn("sensor_id", "=", $sensor_id, "AND")
                -> orderBy("date_time", true)
                -> find();

        $data["measures"] = $sensor_measures;

        DisplayManager::display("sensorstats", $data);
    }

    public static function getSelectSensor(\Entities\Request $req) {

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
            $sensors_list[] = $sensors;
        }

        $all_measure_types = [];
        foreach ($sensors_list as $sl ){
            $type_id = $sl -> getMeasureTypeID();
            $measure_type = (new \Queries\MeasureTypes)
                -> filterByColumn("id" , "=", $type_id, "AND")
                -> findOne();

            $all_measure_types [] = $measure_type;
        }

        $data["measure_types"] = $all_measure_types;
        $data["sensors"] = $sensors_list;
        $data["peripherals"] = $peripherals;

        DisplayManager::display("selectsensorstats", $data);
    }
}