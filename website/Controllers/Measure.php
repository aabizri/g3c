<?php

Namespace Controllers;

Use Entities;
Use Queries;

class Measure
{

    public function GetLastMeasure(Entities\Measure $req): void

    {
        // Si la requête n'est pas associée à un capteur, retourner une erreur
        $sensor_id = $req -> getSensorID();
        if (empty($sensor_id)) {
            http_response_code(403);
            echo "Requête concernant une propriété mais non associée à une propriété, erreur";
            return;
        }

        //Récuperer la dernière mesure

        $last= (new Queries\Measures)
            ->filterBySensorID("=",$sensor_id)
            ->orderBy("date_time",false)
            ->limit(1)
            ->findOne();

        \Helpers\DisplayManager::display("mespieces", ["last" => $last]);
    }
}
?>