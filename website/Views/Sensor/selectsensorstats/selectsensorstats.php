<main>
    <ul id="Menu">
        <li id="Mapropriete"><a href="properties/<?= $data["property"]->getID() ?>"><input type="button"
                                                                                           value="Ma propriété"/></a></li>
        <li id="Mespieces"><a href="properties/<?= $data["property"]->getID() ?>/rooms"><input type="button"
                                                                                               value="Mes pièces"/></a>
        </li>
        <li id="Mesperipheriques"><a href="properties/<?= $data["property"]->getID() ?>/peripherals"><input type="button"
                                                                                                            value="Mes périphériques"/></a>
        </li>
    </ul>

    <h2 id="titre">Historique des mesures d'un capteur</h2>

    <div id="selectionsensor">
    <form method="post" action="index.php?c=Sensorstats&a=SensorStats&debug=true&pid=1">
        <select name="sensor_id">
            <?php
            $sensors = $data["sensors"];
            $peripherals = $data["peripherals"];

            foreach ($peripherals as $p){
                foreach ($sensors as $s) {
                    if ($p->getUUID() === $s->getPeripheralUUID()) {

                        $room_id = $p -> getRoomID();

                        $measure_type_id = $s -> getMeasureTypeID();

                        $room = (new \Queries\Rooms)
                            -> retrieve($room_id);

                        $measure_type = (new \Queries\MeasureTypes)
                            -> retrieve($measure_type_id);

                        echo '<option value="'.$s->getID().'">'.$measure_type->getName().' de la salle : '.$room -> getName().'</option>"';
                    }
                }
            }

            ?>
        </select>
            <input type="submit" value="Valider" />
    </form>
    </div>
</main>