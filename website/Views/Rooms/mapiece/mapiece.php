<main>
    <ul id="Menu">
        <li id="Moncompte"><a class="button" href="account"><input type="button" value="Mon compte"/></a></li>
        <li id="Mespieces"><a class="button" href="properties/<?= $data["pid"] ?>/rooms"><input type="button" value="Mes pièces"/></a>
        </li>
        <li id="Mesperipheriques"><a class="button" href="properties/<?= $data["pid"] ?>/peripherals"><input type="button"
                                                                                              value="Mes périphériques"/></a>
        </li>
    </ul>
<?php
    $room_id = $data["room_entity"] -> getID();
?>


    <a id="lienhistorique" href="properties/<?php $room_entity=$data["room_entity"]; echo $room_entity->getPropertyID();?>/rooms/<?php echo $room_entity->getID();?>/stats">
        <button id="boutonhistorique">Accéder à l'historique des capteurs</button>
    </a>
    <a id="lienconsigne" href="properties/<?= $data["pid"] ?>/room/<?= $data["room_entity"] -> getID() ?>/consignes">
        <button id="boutonconsigne">Accéder aux consignes de la salle</button>
    </a><br/>><br/>


    <h3 id="nomdelapièce">
        <?php
            $room_entity = $data["room_entity"];
            $room_name=$room_entity->getName();
            echo htmlspecialchars($room_name);
            ?>
    </h3>

<div id="vueinformation">
    <h3>Informations sur la pièce</h3>
    <div id="informationspiece">
        <?php
                $last_measures = $data["last_measures"];

                foreach ($last_measures as $measure)
                {
                    echo "<p class=\"temperature\"><strong class='temperatureName'>";
                    $measure_type=(new \Queries\MeasureTypes)-> retrieve($measure -> getTypeID());
                    echo $measure_type->getName()."</strong><br><br>"
                        .$measure->getValue()."". $measure_type->getUnitSymbol()."<br></p>";
                }
                if (empty($last_measures)){
                    echo "Pas de données dans cette salle";
                }

        ?>
    </div>


</div>