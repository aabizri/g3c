<main>
    <ul id="Menu">
        <li id="Moncompte"><a href="account"><input type="button" value="Mon compte"/></a></li>
        <li id="Mespieces"><a href="properties/<?= $data["pid"] ?>/rooms"><input type="button" value="Mes pièces"/></a>
        </li>
        <li id="Mesperipheriques"><a href="properties/<?= $data["pid"] ?>/peripherals"><input type="button"
                                                                                            value="Mes périphériques"/></a>
        </li>
    </ul>

    <h3 id="salleactuelle">
        <?php
        echo $data["room"]->getName();
        ?>
    </h3>

    <div id="champsconsignes">
        <div id="consignes">
            <div id="uneconsigne">
                <?php
                if ($data["actuators"] === []) {
                    echo '<p>Aucune consigne possible, il n\'y a pas de périphérique dans cette salle</p>';
                }

                foreach ($data["actuators"] as $a) {
                    $measure_type = $a->getMeasureType();
                    if ($measure_type === null) {
                        $measure_type_name = "Pas d'unité indiqué";
                    } else {
                        $measure_type_name = $measure_type->getName();
                    }
                    $actuator_id = $a->getID();
                    $last_value = (new Queries\Consignes)
                        ->filterByColumn("actuator_id", "=", $actuator_id)
                        ->orderBy("creation_date", false)
                        ->findOne();

                    if ( $last_value === null ){
                        $value = "Null";
                    }
                    else {
                        $value = $last_value -> getDestinationValue();
                    }


                    echo '<div id="consign"><h4>' . htmlspecialchars($measure_type_name) . ' voulue</h4>
                       <p>Dernière consigne : ' . $value . '</p>
                      <form method="post" action="properties/'.$data["pid"].'/consignescreate">
                        <input type="hidden" value="'. $data["room"]->getID().'" name="room_id" />
                        <input type="hidden" value="' . $value . '" name="last_destination_value" />
                        <input type="hidden" value="' . $a->getID() . '" name="actuator_id" />
                        <input name="destination_value" id="destination_value" type="number" /><br><br>
                        <input type="submit"/>
                      </form></div>';
                }
                ?>
            </div>
        </div>
    </div>

</main>