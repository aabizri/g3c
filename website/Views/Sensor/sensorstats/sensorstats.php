<main>
    <ul id="Menu">
        <li id="Mapropriete"><a class="button" href="properties/<?= $data["property"]->getID() ?>"><input type="button"
                                                                                           value="Ma propriété"/></a></li>
        <li id="Mespieces"><a class="button" href="properties/<?= $data["property"]->getID() ?>/rooms"><input type="button"
                                                                                               value="Mes pièces"/></a>
        </li>
        <li id="Mesperipheriques"><a class="button" href="properties/<?= $data["property"]->getID() ?>/peripherals"><input type="button"
                                                                                                            value="Mes périphériques"/></a>
        </li>
    </ul>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js" ></script>

    <h2 id="titre">Historique des mesures d'un capteur</h2>

    <div id="selectionsensor">
        <form method="post" action="properties/<?php $property=$data["property"]; echo $property->getID();?>/sensorstats">
            <select name="sensor_id" >
                <optgroup label="Sélectionnez un capteur"></optgroup>
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

                            echo '<option value="'.$s->getID().'">'.$measure_type->getName().' de la salle : '.$room -> getName().'</option>';
                        }
                    }
                }

                ?>
            </select>
            <input type="submit" value="Valider" />
        </form>
    </div>


    <div id="stats">
    <canvas id="myChart" ></canvas>

    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $data["dates"] ?>,
                datasets: [{
                    label: <?= $data["name"] ?>,
                    data: <?= $data["values"] ?>,
                    backgroundColor: [
                        'rgba(222, 184, 135, 0.4)'
                    ],
                    borderColor: [
                        'rgba(128,000,000,0.8)'
                    ],

                    borderWidth: 1
                }]
            },
            options: {
                title: {
                    display: true,
                    text:<?= $data["roomname"] ?>
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                        }
                    }]
                }
            }
        });
    </script>
</div>
</main>