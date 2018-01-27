<main>
    <ul id="Menu">
        <li id="Moncompte"><a href="account"><input type="button" value="Mon compte"/></a></li>
        <li id="Mespieces"><a href="properties/<?= $data["pid"] ?>/rooms"><input type="button" value="Mes pièces"/></a>
        </li>
        <li id="Mesperipheriques"><a href="properties/<?= $data["pid"] ?>/peripherals"><input type="button"
                                                                                              value="Mes périphériques"/></a>
        </li>
        <li id="Mesconsignes"><a href="properties/<?= $data["pid"] ?>/consignes"><input type="button"
                                                                                        value="Mes Consignes"/></a></li>
    </ul>


    <h3 id="nomdelapièce">
        <?php
            $room_entity=$data["room_entity"];
            $room_name=$room_entity->getName();
            echo $room_name;
            ?>
    </h3>

<div id="vueinformation">
    <h3>Informations sur la pièce</h3>
    <div id="informationspiece">
        <?php
                $last_measures = $data["last_measures"];

                foreach ($last_measures as $measure)
                {
                    echo "<p class=\"temperature\"><strong>";
                    $measure_type=(new \Queries\MeasureTypes)-> retrieve($measure -> getTypeID());
                    echo $measure_type->getName()."</strong><br><br>"
                        .$measure->getValue()."". $measure_type->getUnitSymbol()."<br></p>";
                }

        ?>
    </div>


</div>
