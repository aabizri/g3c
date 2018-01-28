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


<h2 id="nompiece"> Choix de la salle</h2>
<?php
if (!empty($data["rooms"])):
?>

    <a id="lienhistorique" href="index.php?c=Sensorstats&a=SelectSensor&pid=1">
        <button id="boutonhistorique">Accéder à l'historique des capteurs</button>
    </a>

<form method="get" action="index.php" id="choixsalle">
    <input type="hidden" name="c" value="Room"/>
    <input type="hidden" name="a" value="Room"/>
    <select name="room">
        <?php
        $rooms = $data["rooms"];
        foreach ($rooms as $room) {
            echo '<option value="'.$room->getID().'">'.$room->getName().'</option>';
        }
        ?>
    </select>

    <input type="submit" value="Valider" title="Valider pour accéder à la salle" />

</form>
    <h3 id="nomdelapièce">
        <?php
            $room_entity=$data["room_entity"];
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
                    echo "<p class=\"temperature\"><strong>";
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
